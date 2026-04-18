<?php

declare(strict_types=1);

namespace App\Domain\LicenseImport\Reporting;

use App\Models\LicenseImportBatch;
use BackedEnum;

final class TelematBatchReportBuilder
{
    public function build(LicenseImportBatch $batch): array
    {
        $batch->loadMissing(['issues']);

        $summary = is_array($batch->summary) ? $batch->summary : [];
        $meta = is_array($batch->meta) ? $batch->meta : [];

        return [
            'batch' => [
                'id' => $batch->getKey(),
                'source' => $batch->source,
                'status' => $this->normalizeEnumValue($batch->status),
                'is_active' => (bool) $batch->is_active,
                'trigger' => [
                    'type' => $this->normalizeEnumValue($batch->trigger_type),
                    'label' => $batch->triggered_by_label,
                    'user_id' => $batch->triggered_by_user_id,
                ],
                'started_at' => $batch->started_at?->toIso8601String(),
                'finished_at' => $batch->finished_at?->toIso8601String(),
                'activated_at' => $batch->activated_at?->toIso8601String(),
            ],
            'execution' => [
                'raw_rows_count' => $batch->raw_rows_count,
                'valid_rows_count' => $batch->valid_rows_count,
                'invalid_rows_count' => $batch->invalid_rows_count,
            ],
            'validation' => [
                'minimal' => is_array($summary['minimal_validation'] ?? null)
                    ? $summary['minimal_validation']
                    : null,
                'comparative' => is_array($summary['comparative_validation'] ?? null)
                    ? $summary['comparative_validation']
                    : null,
            ],
            'issues' => [
                'error_count' => $batch->error_count,
                'warning_count' => $batch->warning_count,
                'items' => $batch->issues
                    ->sortBy('id')
                    ->map(fn ($issue) => [
                        'code' => $issue->code,
                        'severity' => $this->normalizeEnumValue($issue->severity),
                        'message' => $issue->message,
                        'row_index' => $issue->row_index,
                        'context' => is_array($issue->context) ? $issue->context : [],
                        'created_at' => $issue->created_at?->toIso8601String(),
                    ])
                    ->values()
                    ->all(),
            ],
            'activation' => [
                'activated' => (bool) $batch->is_active,
                'activated_at' => $batch->activated_at?->toIso8601String(),
                'final_outcome' => $meta['execution']['final_outcome'] ?? null,
                'reason' => $summary['activation']['reason'] ?? null,
            ],
            'projection' => [
                'executed' => (bool) ($summary['projection']['executed'] ?? false),
                'failed' => (bool) ($summary['projection']['failed'] ?? false),
                'reason' => $summary['projection']['reason'] ?? null,
                'strategy' => $summary['projection']['strategy'] ?? null,
                'source_snapshot_count' => (int) ($summary['projection']['source_snapshot_count'] ?? 0),
                'deleted_count' => (int) ($summary['projection']['deleted_count'] ?? 0),
                'inserted_count' => (int) ($summary['projection']['inserted_count'] ?? 0),
                'updated_count' => (int) ($summary['projection']['updated_count'] ?? 0),
                'unchanged_count' => (int) ($summary['projection']['unchanged_count'] ?? 0),
                'no_op' => (bool) ($summary['projection']['no_op'] ?? false),
                'context' => is_array($summary['projection']['context'] ?? null)
                    ? $summary['projection']['context']
                    : null,
                'diff' => is_array($summary['projection']['diff'] ?? null)
                    ? $summary['projection']['diff']
                    : null,
            ],
            'result' => [
                'success' => $this->isSuccess($batch, $meta),
                'blocking_failure' => $this->hasBlockingFailure($batch, $meta),
                'failure_reason' => $meta['failure']['failure_code'] ?? null,
            ],
        ];
    }

    private function isSuccess(LicenseImportBatch $batch, array $meta): bool
    {
        return ($meta['execution']['result'] ?? null) === 'succeeded'
            || ((string) $this->normalizeEnumValue($batch->status) !== 'failed' && empty($meta['failure']));
    }

    private function hasBlockingFailure(LicenseImportBatch $batch, array $meta): bool
    {
        return (string) $this->normalizeEnumValue($batch->status) === 'failed'
            || is_array($meta['failure'] ?? null);
    }

    private function normalizeEnumValue(mixed $value): mixed
    {
        return $value instanceof BackedEnum
            ? $value->value
            : $value;
    }
}