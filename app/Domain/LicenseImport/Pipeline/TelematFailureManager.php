<?php

declare(strict_types=1);

namespace App\Domain\LicenseImport\Pipeline;

use App\Domain\LicenseImport\DTO\LicenseImportExecutionContext;
use App\Domain\LicenseImport\Enums\BatchStatus;
use App\Domain\LicenseImport\Enums\IssueSeverity;
use App\Domain\LicenseImport\Fetch\Exceptions\TelematFetchException;
use App\Domain\LicenseImport\Fetch\Exceptions\TelematInvalidResponseException;
use App\Models\LicenseImportBatch;
use App\Domain\LicenseImport\Reporting\TelematBatchReportManager;
use Throwable;

final class TelematFailureManager
{
    public function __construct(
        private readonly TelematIssueRecorder $issueRecorder,
        private readonly TelematBatchLifecycleManager $batchLifecycleManager,
        private readonly TelematPipelineLogger $logger,
        private readonly TelematBatchReportManager $batchReportManager,
    ) {
    }

    public function handleFetchFailure(
        LicenseImportBatch $batch,
        LicenseImportExecutionContext $context,
        TelematFetchException $exception,
    ): void {
        $issueCode = $this->issueRecorder->resolveFetchIssueCode($exception);
        $issueContext = $this->issueRecorder->makeContext($context, $exception);

        if ($exception instanceof TelematInvalidResponseException) {
            $issueContext['reason'] = $exception->reason;
            $issueContext['response_context'] = $exception->context;
        }

        $this->logger->error('license_import.pipeline.fetch_failed', [
            'batch_id' => $batch->getKey(),
            'issue_code' => $issueCode,
            'message' => $exception->getMessage(),
            'context' => $issueContext,
        ]);

        $this->issueRecorder->record(
            batch: $batch,
            severity: IssueSeverity::Error,
            code: $issueCode,
            message: $exception->getMessage(),
            context: $issueContext,
            rowIndex: null,
        );

        $this->markBatchAsFailed($batch, [
            'failure_stage' => 'fetch',
            'failure_code' => $issueCode,
            'failure_message' => $exception->getMessage(),
        ]);
    }

    public function handleStageFailure(
        LicenseImportBatch $batch,
        LicenseImportExecutionContext $context,
        Throwable $exception,
        string $failureStage,
        string $failureCode,
    ): void {
        $issueContext = $this->issueRecorder->makeContext($context, $exception);
        $issueContext['failure_stage'] = $failureStage;

        $this->logger->error('license_import.pipeline.stage_failed', [
            'batch_id' => $batch->getKey(),
            'failure_stage' => $failureStage,
            'failure_code' => $failureCode,
            'message' => $exception->getMessage(),
            'context' => $issueContext,
        ]);

        $this->issueRecorder->record(
            batch: $batch,
            severity: IssueSeverity::Error,
            code: $failureCode,
            message: $exception->getMessage(),
            context: $issueContext,
            rowIndex: null,
        );

        $this->markBatchAsFailed($batch, [
            'failure_stage' => $failureStage,
            'failure_code' => $failureCode,
            'failure_message' => $exception->getMessage(),
        ]);
    }

    public function handleUnexpectedFailure(
        LicenseImportBatch $batch,
        LicenseImportExecutionContext $context,
        Throwable $exception,
    ): void {
        $issueContext = $this->issueRecorder->makeContext($context, $exception);

        $this->logger->error('license_import.pipeline.unexpected_failure', [
            'batch_id' => $batch->getKey(),
            'message' => $exception->getMessage(),
            'context' => $issueContext,
        ]);

        $this->issueRecorder->record(
            batch: $batch,
            severity: IssueSeverity::Error,
            code: 'pipeline_unexpected_failure',
            message: $exception->getMessage(),
            context: $issueContext,
            rowIndex: null,
        );

        $this->markBatchAsFailed($batch, [
            'failure_stage' => 'pipeline',
            'failure_code' => 'pipeline_unexpected_failure',
            'failure_message' => $exception->getMessage(),
        ]);
    }

    /**
     * @param array<string, mixed> $meta
     */
    private function markBatchAsFailed(LicenseImportBatch $batch, array $meta = []): void
    {
        $failedAt = now();

        $existingMeta = is_array($batch->meta) ? $batch->meta : [];
        $existingSummary = is_array($batch->summary) ? $batch->summary : [];

        $mergedMeta = array_replace_recursive($existingMeta, [
            'execution' => [
                'finished' => true,
                'result' => 'failed',
                'final_outcome' => 'failed',
                'finished_at' => $failedAt->toIso8601String(),
            ],
            'failure' => $meta,
        ]);

        $mergedSummary = array_replace_recursive($existingSummary, [
            'execution' => [
                'finished' => true,
                'result' => 'failed',
                'final_outcome' => 'failed',
                'failure_stage' => $meta['failure_stage'] ?? null,
                'failure_code' => $meta['failure_code'] ?? null,
            ],
        ]);

        $batch->forceFill([
            'status' => BatchStatus::Failed,
            'is_active' => false,
            'finished_at' => $failedAt,
            'meta' => $mergedMeta,
            'summary' => $mergedSummary,
        ])->save();

        $this->batchLifecycleManager->refreshIssueCounters($batch);
        $batch->refresh();

        $this->batchReportManager->writeSafely($batch);
    }
}