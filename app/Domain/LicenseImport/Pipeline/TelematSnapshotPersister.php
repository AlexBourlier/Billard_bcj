<?php

declare(strict_types=1);

namespace App\Domain\LicenseImport\Pipeline;

use App\Domain\LicenseImport\DTO\LicenseImportExecutionContext;
use App\Models\LicenseImportBatch;
use App\Models\LicenseImportSnapshot;

final class TelematSnapshotPersister
{
    public function __construct(
        private readonly TelematPipelineLogger $logger,
    ) {
    }

    /**
     * @param array<int, string> $parsedHeaders
     * @param array<int, array<string, mixed>> $rows
     */
    public function persist(
        LicenseImportBatch $batch,
        array $parsedHeaders,
        array $rows,
        LicenseImportExecutionContext $context,
    ): void {
        $now = now();

        $snapshotPayloads = [];

        foreach ($rows as $row) {
            if (!is_array($row)) {
                continue;
            }

            $sourceRow = is_array($row['source_row'] ?? null)
                ? $row['source_row']
                : [];

            $normalizedSourceRow = is_array($row['normalized_source_row'] ?? null)
                ? $row['normalized_source_row']
                : [];

            $snapshotPayloads[] = [
                'import_batch_id' => $batch->getKey(),
                'row_index' => (int) ($row['row_index'] ?? 0),
                'source_row_hash' => $this->computeSourceRowHash($sourceRow),
                'is_valid' => true,
                'source_license_number' => $this->nullableStringValue($row['source_license_number'] ?? null),
                'license_number' => $this->nullableStringValue($row['license_number'] ?? null),
                'last_name' => $this->nullableStringValue($row['last_name'] ?? null),
                'first_name' => $this->nullableStringValue($row['first_name'] ?? null),
                'birth_date' => $this->nullableDateValue($row['birth_date'] ?? null),
                'gender' => $this->nullableStringValue($row['gender'] ?? null),
                'status' => $this->nullableStringValue($row['status'] ?? null),
                'category' => $this->nullableStringValue($row['category'] ?? null),
                'license_type' => $this->nullableStringValue($row['license_type'] ?? null),
                'season' => $this->nullableStringValue($row['season'] ?? null),
                'source_status_label' => $this->nullableStringValue($row['source_status_label'] ?? null),
                'source_category_label' => $this->nullableStringValue($row['source_category_label'] ?? null),
                'extra_data' => json_encode(
                    [
                        'source_row' => $sourceRow,
                        'normalized_source_row' => $normalizedSourceRow,
                    ],
                    JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
                ),
                'validation_flags' => json_encode(
                    [],
                    JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
                ),
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        if ($snapshotPayloads !== []) {
            LicenseImportSnapshot::query()->insert($snapshotPayloads);
        }

        $batch->forceFill([
            'raw_rows_count' => count($snapshotPayloads),
            'valid_rows_count' => count($snapshotPayloads),
            'invalid_rows_count' => 0,
            'source_columns' => $parsedHeaders,
            'source_fingerprint' => $this->computeSourceFingerprint($rows),
        ])->save();

        $this->logger->info('license_import.pipeline.persist_snapshots.completed', [
            'batch_id' => $batch->getKey(),
            'source' => $context->source,
            'snapshots_count' => count($snapshotPayloads),
            'source_columns_count' => count($parsedHeaders),
            'source_columns' => $parsedHeaders,
        ]);
    }

    /**
     * @param array<string, mixed> $sourceRow
     */
    private function computeSourceRowHash(array $sourceRow): string
    {
        ksort($sourceRow);

        $json = json_encode(
            $sourceRow,
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_INVALID_UTF8_SUBSTITUTE,
        );

        return hash('sha256', $json === false ? '' : $json);
    }

    /**
     * @param array<int, array<string, mixed>> $rows
     */
    private function computeSourceFingerprint(array $rows): string
    {
        $hashes = [];

        foreach ($rows as $row) {
            if (!is_array($row)) {
                continue;
            }

            $sourceRow = is_array($row['source_row'] ?? null)
                ? $row['source_row']
                : [];

            $hashes[] = $this->computeSourceRowHash($sourceRow);
        }

        sort($hashes);

        return hash('sha256', implode('|', $hashes));
    }

    private function nullableStringValue(mixed $value): ?string
    {
        if (!is_string($value)) {
            return null;
        }

        $normalized = trim($value);

        return $normalized === '' ? null : $normalized;
    }

    private function nullableDateValue(mixed $value): ?string
    {
        if (!is_string($value)) {
            return null;
        }

        $normalized = trim($value);

        return $normalized === '' ? null : $normalized;
    }
}