<?php

declare(strict_types=1);

namespace App\Domain\LicenseImport\Pipeline;

use App\Domain\LicenseImport\DTO\LicenseImportExecutionContext;
use App\Domain\LicenseImport\Enums\BatchStatus;
use App\Models\LicenseImportBatch;

final class TelematBatchLifecycleManager
{
    public function __construct(
        private readonly TelematPipelineLogger $logger,
    ) {
    }

    public function start(LicenseImportExecutionContext $context): LicenseImportBatch
    {
        $startedAt = now();

        $batch = new LicenseImportBatch();

        $batch->forceFill([
            'source' => $context->source,
            'status' => BatchStatus::Running,
            'is_active' => false,
            'activated_at' => null,
            'started_at' => $startedAt,
            'finished_at' => null,
            'trigger_type' => $context->triggerType,
            'triggered_by_user_id' => $context->triggeredByUserId,
            'triggered_by_label' => $context->triggeredByLabel,
            'raw_rows_count' => 0,
            'valid_rows_count' => 0,
            'invalid_rows_count' => 0,
            'error_count' => 0,
            'warning_count' => 0,
            'source_fingerprint' => null,
            'source_columns' => null,
            'meta' => [
                'execution' => [
                    'requested_at' => $context->requestedAt->toIso8601String(),
                    'started_at' => $startedAt->toIso8601String(),
                    'dry_run' => $context->dryRun,
                    'finished' => false,
                    'result' => null,
                    'final_outcome' => null,
                    'finished_at' => null,
                ],
            ],
            'summary' => [
                'issue_counters' => [
                    'error_count' => 0,
                    'warning_count' => 0,
                ],
            ],
        ]);

        $batch->save();

        $this->logger->info('license_import.pipeline.batch_started', [
            'batch_id' => $batch->getKey(),
            'source' => $context->source,
            'trigger_type' => $context->triggerType->value,
            'triggered_by_user_id' => $context->triggeredByUserId,
            'triggered_by_label' => $context->triggeredByLabel,
            'requested_at' => $context->requestedAt->toIso8601String(),
            'dry_run' => $context->dryRun,
        ]);

        return $batch;
    }

    public function transition(LicenseImportBatch $batch, BatchStatus $status): void
    {
        $batch->forceFill([
            'status' => $status,
        ])->save();
    }

    public function refreshIssueCounters(LicenseImportBatch $batch): void
    {
        $errorCount = $batch->issues()->errors()->count();
        $warningCount = $batch->issues()->warnings()->count();

        $existingSummary = is_array($batch->summary) ? $batch->summary : [];

        $batch->forceFill([
            'error_count' => $errorCount,
            'warning_count' => $warningCount,
            'summary' => array_replace_recursive($existingSummary, [
                'issue_counters' => [
                    'error_count' => $errorCount,
                    'warning_count' => $warningCount,
                ],
            ]),
        ])->save();
    }
}