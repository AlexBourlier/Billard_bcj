<?php

declare(strict_types=1);

namespace App\Domain\LicenseImport\Pipeline;

use App\Domain\LicenseImport\Activation\TelematBatchActivator;
use App\Domain\LicenseImport\DTO\LicenseImportExecutionContext;
use App\Domain\LicenseImport\Enums\BatchStatus;
use App\Domain\LicenseImport\Fetch\Contracts\TelematFetcherInterface;
use App\Domain\LicenseImport\Fetch\Exceptions\TelematFetchException;
use App\Domain\LicenseImport\Fetch\TelematFetchConfigFactory;
use App\Domain\LicenseImport\Fetch\TelematFetchResult;
use App\Domain\LicenseImport\Normalization\TelematRowNormalizer;
use App\Domain\LicenseImport\Parsing\TelematHtmlTableParser;
use App\Domain\LicenseImport\Pipeline\Exceptions\BatchActivationException;
use App\Domain\LicenseImport\Pipeline\Exceptions\ComparativeValidationFailedException;
use App\Domain\LicenseImport\Pipeline\Exceptions\MinimalValidationFailedException;
use App\Domain\LicenseImport\Pipeline\Exceptions\TelematHtmlParseException;
use App\Domain\LicenseImport\Projection\ProjectionResult;
use App\Domain\LicenseImport\Projection\TelematLicenseProjector;
use App\Domain\LicenseImport\Projection\TelematProjectionEligibilityDecider;
use App\Domain\LicenseImport\Reporting\TelematBatchReportManager;
use App\Domain\LicenseImport\Validation\TelematComparativeBatchValidator;
use App\Domain\LicenseImport\Validation\TelematMinimalBatchValidator;
use App\Models\LicenseImportBatch;
use Throwable;

final class TelematLicenseImportOrchestrator
{
    public function __construct(
        private readonly TelematFetchConfigFactory $fetchConfigFactory,
        private readonly TelematFetcherInterface $httpFetcher,
        private readonly TelematBatchLifecycleManager $batchLifecycleManager,
        private readonly TelematHtmlTableParser $htmlTableParser,
        private readonly TelematRowNormalizer $rowNormalizer,
        private readonly TelematSnapshotPersister $snapshotPersister,
        private readonly TelematMinimalBatchValidator $minimalBatchValidator,
        private readonly TelematComparativeBatchValidator $comparativeBatchValidator,
        private readonly TelematBatchActivator $batchActivator,
        private readonly TelematFailureManager $failureManager,
        private readonly TelematPipelineLogger $logger,
        private readonly TelematBatchReportManager $batchReportManager,
        private readonly TelematLicenseProjector $licenseProjector,
        private readonly TelematProjectionEligibilityDecider $projectionEligibilityDecider,
    ) {
    }

    public function run(LicenseImportExecutionContext $context): LicenseImportBatch
    {
        $batch = $this->batchLifecycleManager->start($context);

        try {
            $fetchResult = $this->dispatchFetch($context);
            $this->batchLifecycleManager->transition($batch, BatchStatus::Fetched);

            $parsedTable = $this->htmlTableParser->parse($fetchResult->html, $context);
            $this->batchLifecycleManager->transition($batch, BatchStatus::Parsed);

            $normalizedRows = $this->rowNormalizer->normalize($parsedTable, $context);

            $this->snapshotPersister->persist(
                batch: $batch,
                parsedHeaders: $parsedTable['headers'],
                rows: $normalizedRows,
                context: $context,
            );

            $this->minimalBatchValidator->validate($batch, $context);
            $this->batchLifecycleManager->transition($batch, BatchStatus::ValidatedMinimal);

            $this->comparativeBatchValidator->validate($batch, $context);
            $this->batchLifecycleManager->transition($batch, BatchStatus::ValidatedComparative);

            $this->batchActivator->activate($batch, $context);
            $batch->refresh();

            $projectionResult = $this->resolveProjectionResult($batch, $context);

            $this->appendProjectionSummary($batch, $projectionResult);

            $this->batchLifecycleManager->refreshIssueCounters($batch);
            $batch->refresh();

            $this->logCompletion($batch, $context);

            $this->batchReportManager->writeSafely($batch);

            return $batch;
        } catch (TelematFetchException $exception) {
            $this->failureManager->handleFetchFailure($batch, $context, $exception);
            throw $exception;
        } catch (TelematHtmlParseException $exception) {
            $this->failureManager->handleStageFailure(
                $batch,
                $context,
                $exception,
                'parse',
                'parse_failed',
            );
            throw $exception;
        } catch (MinimalValidationFailedException $exception) {
            $this->failureManager->handleStageFailure(
                $batch,
                $context,
                $exception,
                'minimal_validation',
                'minimal_validation_failed',
            );
            throw $exception;
        } catch (ComparativeValidationFailedException $exception) {
            $this->failureManager->handleStageFailure(
                $batch,
                $context,
                $exception,
                'comparative_validation',
                'comparative_validation_failed',
            );
            throw $exception;
        } catch (BatchActivationException $exception) {
            $this->failureManager->handleStageFailure(
                $batch,
                $context,
                $exception,
                'activation',
                'activation_failed',
            );
            throw $exception;
        } catch (Throwable $exception) {
            $this->failureManager->handleUnexpectedFailure($batch, $context, $exception);
            throw $exception;
        }
    }

    private function dispatchFetch(LicenseImportExecutionContext $context): TelematFetchResult
    {
        $config = $this->fetchConfigFactory->make();

        $this->logger->info('license_import.pipeline.fetch.dispatching', [
            'source' => $context->source,
            'trigger_type' => $context->triggerType->value,
            'triggered_by' => $context->triggeredByLabel,
            'dry_run' => $context->dryRun,
            'url' => $config->url,
            'method' => $config->method,
        ]);

        return $this->httpFetcher->fetch($config);
    }

    private function resolveProjectionResult(
        LicenseImportBatch $batch,
        LicenseImportExecutionContext $context,
    ): ProjectionResult {
        $strategyName = $this->licenseProjector->strategyName();

        $decision = $this->projectionEligibilityDecider->decide(
            $batch,
            $context,
            $strategyName,
        );

        if ($decision->shouldSkip()) {
            return ProjectionResult::skipped($decision->reason ?? 'projection_skipped');
        }

        if ($decision->isPreview()) {
            return $this->licenseProjector->preview($batch);
        }

        return $this->licenseProjector->project($batch);
    }

    private function appendProjectionSummary(
        LicenseImportBatch $batch,
        ProjectionResult $projectionResult,
    ): void {
        $summary = is_array($batch->summary) ? $batch->summary : [];

        $summary['projection'] = [
            'executed' => $projectionResult->executed,
            'failed' => $projectionResult->failed,
            'reason' => $projectionResult->reason,
            'strategy' => $this->licenseProjector->strategyName(),
            'source_snapshot_count' => $projectionResult->sourceSnapshotCount,
            'deleted_count' => $projectionResult->deletedCount,
            'inserted_count' => $projectionResult->insertedCount,
            'updated_count' => $projectionResult->updatedCount,
            'unchanged_count' => $projectionResult->unchangedCount,
            'no_op' => $projectionResult->noOp,
            'preview' => is_array($projectionResult->context['preview'] ?? null)
                ? $projectionResult->context['preview']
                : null,
            'context' => $projectionResult->context,
            'diff' => is_array($projectionResult->diff) ? $projectionResult->diff : null,
        ];

        $batch->forceFill([
            'summary' => $summary,
        ])->save();
    }

    private function logCompletion(
        LicenseImportBatch $batch,
        LicenseImportExecutionContext $context,
    ): void {
        $this->logger->info('license_import.pipeline.completed', [
            'batch_id' => $batch->getKey(),
            'source' => $context->source,
            'trigger_type' => $context->triggerType->value,
            'triggered_by_user_id' => $context->triggeredByUserId,
            'triggered_by_label' => $context->triggeredByLabel,
            'dry_run' => $context->dryRun,
            'execution_result' => data_get($batch->summary, 'execution.result'),
            'execution_final_outcome' => data_get($batch->summary, 'execution.final_outcome'),
            'projection_executed' => data_get($batch->summary, 'projection.executed'),
            'projection_reason' => data_get($batch->summary, 'projection.reason'),
            'projection_updated_count' => data_get($batch->summary, 'projection.updated_count'),
            'projection_unchanged_count' => data_get($batch->summary, 'projection.unchanged_count'),
            'projection_no_op' => data_get($batch->summary, 'projection.no_op'),
        ]);
    }
}