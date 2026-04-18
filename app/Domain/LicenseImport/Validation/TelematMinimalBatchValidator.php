<?php

declare(strict_types=1);

namespace App\Domain\LicenseImport\Validation;

use App\Domain\LicenseImport\DTO\LicenseImportExecutionContext;
use App\Domain\LicenseImport\Enums\IssueSeverity;
use App\Domain\LicenseImport\Pipeline\Exceptions\MinimalValidationFailedException;
use App\Domain\LicenseImport\Pipeline\TelematBatchLifecycleManager;
use App\Domain\LicenseImport\Pipeline\TelematIssueRecorder;
use App\Domain\LicenseImport\Pipeline\TelematPipelineLogger;
use App\Models\LicenseImportBatch;
use App\Models\LicenseImportSnapshot;
use RuntimeException;

final class TelematMinimalBatchValidator
{
    public function __construct(
        private readonly TelematIssueRecorder $issueRecorder,
        private readonly TelematBatchLifecycleManager $batchLifecycleManager,
        private readonly TelematPipelineLogger $logger,
    ) {
    }

    public function validate(LicenseImportBatch $batch, LicenseImportExecutionContext $context): void
    {
        $validationConfig = config('license_import.validation.minimal', []);
        $mappingConfig = config('license_import.mapping', []);

        if (!is_array($validationConfig)) {
            throw new RuntimeException('The license_import.validation.minimal config must be an array.');
        }

        if (!is_array($mappingConfig)) {
            throw new RuntimeException('The license_import.mapping config must be an array.');
        }

        $minimumRawRowsCount = (int) ($validationConfig['minimum_raw_rows_count'] ?? 0);
        $minimumValidRatioPercent = (int) ($validationConfig['minimum_valid_ratio_percent'] ?? 0);
        $maximumInvalidRatioPercent = (int) ($validationConfig['maximum_invalid_ratio_percent'] ?? 100);
        $requiredFieldFillRatePercent = is_array($validationConfig['required_field_fill_rate_percent'] ?? null)
            ? $validationConfig['required_field_fill_rate_percent']
            : [];
        $duplicateLicenseNumberPolicy = (string) ($validationConfig['duplicate_license_number_policy'] ?? 'warning');

        $requiredFields = is_array($mappingConfig['required_fields'] ?? null)
            ? array_values(array_filter(
                $mappingConfig['required_fields'],
                static fn ($field): bool => is_string($field) && trim($field) !== '',
            ))
            : [];

        $snapshots = $batch->snapshots()->orderBy('row_index')->get();
        $rawRowsCount = $snapshots->count();

        if ($rawRowsCount < $minimumRawRowsCount) {
            $this->issueRecorder->record(
                batch: $batch,
                severity: IssueSeverity::Error,
                code: 'minimal_raw_rows_below_threshold',
                message: sprintf(
                    'The batch contains fewer raw rows than required [%d < %d].',
                    $rawRowsCount,
                    $minimumRawRowsCount,
                ),
                context: [
                    'raw_rows_count' => $rawRowsCount,
                    'minimum_raw_rows_count' => $minimumRawRowsCount,
                    'source' => $context->source,
                ],
            );
        }

        $fillCounters = [];
        foreach ($requiredFields as $field) {
            $fillCounters[$field] = 0;
        }

        $licenseNumberIndex = [];
        $duplicateLicenseNumbers = [];

        foreach ($snapshots as $snapshot) {
            $validationFlags = [];
            $isValid = true;

            foreach ($requiredFields as $field) {
                $value = $snapshot->{$field} ?? null;

                if (is_string($value) && trim($value) !== '') {
                    $fillCounters[$field]++;
                } else {
                    $validationFlags[] = sprintf('missing_required_field:%s', $field);
                    $isValid = false;

                    $this->issueRecorder->record(
                        batch: $batch,
                        severity: IssueSeverity::Warning,
                        code: 'missing_required_field',
                        message: sprintf(
                            'Required field "%s" is missing for row %d.',
                            $field,
                            $snapshot->row_index,
                        ),
                        context: [
                            'field' => $field,
                            'row_index' => $snapshot->row_index,
                            'snapshot_id' => $snapshot->getKey(),
                        ],
                        rowIndex: $snapshot->row_index,
                    );
                }
            }

            $licenseNumber = is_string($snapshot->license_number) ? trim($snapshot->license_number) : '';

            if ($licenseNumber !== '') {
                $licenseNumberIndex[$licenseNumber][] = $snapshot;
            }

            $snapshot->forceFill([
                'is_valid' => $isValid,
                'validation_flags' => $validationFlags,
            ])->save();
        }

        foreach ($licenseNumberIndex as $licenseNumber => $licenseSnapshots) {
            if (count($licenseSnapshots) <= 1) {
                continue;
            }

            $duplicateLicenseNumbers[$licenseNumber] = array_map(
                static fn (LicenseImportSnapshot $snapshot): int => (int) $snapshot->row_index,
                $licenseSnapshots,
            );

            if ($duplicateLicenseNumberPolicy === 'reject') {
                foreach ($licenseSnapshots as $snapshot) {
                    $flags = is_array($snapshot->validation_flags) ? $snapshot->validation_flags : [];
                    $flags[] = 'duplicate_license_number';

                    $snapshot->forceFill([
                        'is_valid' => false,
                        'validation_flags' => array_values(array_unique($flags)),
                    ])->save();
                }

                $this->issueRecorder->record(
                    batch: $batch,
                    severity: IssueSeverity::Error,
                    code: 'duplicate_license_number_rejected',
                    message: sprintf(
                        'Duplicate license number "%s" detected and rejected.',
                        $licenseNumber,
                    ),
                    context: [
                        'license_number' => $licenseNumber,
                        'row_indexes' => $duplicateLicenseNumbers[$licenseNumber],
                        'policy' => $duplicateLicenseNumberPolicy,
                    ],
                );
            } elseif ($duplicateLicenseNumberPolicy === 'warning') {
                $this->issueRecorder->record(
                    batch: $batch,
                    severity: IssueSeverity::Warning,
                    code: 'duplicate_license_number_warning',
                    message: sprintf(
                        'Duplicate license number "%s" detected.',
                        $licenseNumber,
                    ),
                    context: [
                        'license_number' => $licenseNumber,
                        'row_indexes' => $duplicateLicenseNumbers[$licenseNumber],
                        'policy' => $duplicateLicenseNumberPolicy,
                    ],
                );
            }
        }

        $validRowsCount = $batch->snapshots()->valid()->count();
        $invalidRowsCount = $batch->snapshots()->invalid()->count();

        $validRatioPercent = $rawRowsCount > 0
            ? (int) floor(($validRowsCount / $rawRowsCount) * 100)
            : 0;

        $invalidRatioPercent = $rawRowsCount > 0
            ? (int) floor(($invalidRowsCount / $rawRowsCount) * 100)
            : 0;

        $hasBlockingFailure = false;

        if ($validRatioPercent < $minimumValidRatioPercent) {
            $hasBlockingFailure = true;

            $this->issueRecorder->record(
                batch: $batch,
                severity: IssueSeverity::Error,
                code: 'minimum_valid_ratio_not_reached',
                message: sprintf(
                    'Valid rows ratio is below the configured threshold [%d%% < %d%%].',
                    $validRatioPercent,
                    $minimumValidRatioPercent,
                ),
                context: [
                    'valid_rows_count' => $validRowsCount,
                    'raw_rows_count' => $rawRowsCount,
                    'valid_ratio_percent' => $validRatioPercent,
                    'minimum_valid_ratio_percent' => $minimumValidRatioPercent,
                ],
            );
        }

        if ($invalidRatioPercent > $maximumInvalidRatioPercent) {
            $hasBlockingFailure = true;

            $this->issueRecorder->record(
                batch: $batch,
                severity: IssueSeverity::Error,
                code: 'maximum_invalid_ratio_exceeded',
                message: sprintf(
                    'Invalid rows ratio is above the configured threshold [%d%% > %d%%].',
                    $invalidRatioPercent,
                    $maximumInvalidRatioPercent,
                ),
                context: [
                    'invalid_rows_count' => $invalidRowsCount,
                    'raw_rows_count' => $rawRowsCount,
                    'invalid_ratio_percent' => $invalidRatioPercent,
                    'maximum_invalid_ratio_percent' => $maximumInvalidRatioPercent,
                ],
            );
        }

        foreach ($requiredFieldFillRatePercent as $field => $minimumPercent) {
            if (!is_string($field) || !array_key_exists($field, $fillCounters)) {
                continue;
            }

            $minimumPercent = (int) $minimumPercent;

            $actualPercent = $rawRowsCount > 0
                ? (int) floor(($fillCounters[$field] / $rawRowsCount) * 100)
                : 0;

            if ($actualPercent >= $minimumPercent) {
                continue;
            }

            $hasBlockingFailure = true;

            $this->issueRecorder->record(
                batch: $batch,
                severity: IssueSeverity::Error,
                code: 'required_field_fill_rate_below_threshold',
                message: sprintf(
                    'Required field fill rate for "%s" is below the configured threshold [%d%% < %d%%].',
                    $field,
                    $actualPercent,
                    $minimumPercent,
                ),
                context: [
                    'field' => $field,
                    'filled_rows_count' => $fillCounters[$field],
                    'raw_rows_count' => $rawRowsCount,
                    'actual_fill_rate_percent' => $actualPercent,
                    'minimum_fill_rate_percent' => $minimumPercent,
                ],
            );
        }

        if ($duplicateLicenseNumberPolicy === 'reject' && $duplicateLicenseNumbers !== []) {
            $hasBlockingFailure = true;
        }

        $existingSummary = is_array($batch->summary) ? $batch->summary : [];

        $batch->forceFill([
            'raw_rows_count' => $rawRowsCount,
            'valid_rows_count' => $validRowsCount,
            'invalid_rows_count' => $invalidRowsCount,
            'summary' => array_replace_recursive($existingSummary, [
                'minimal_validation' => [
                    'raw_rows_count' => $rawRowsCount,
                    'valid_rows_count' => $validRowsCount,
                    'invalid_rows_count' => $invalidRowsCount,
                    'valid_ratio_percent' => $validRatioPercent,
                    'invalid_ratio_percent' => $invalidRatioPercent,
                    'required_field_fill_rates' => $this->computeRequiredFieldFillRates(
                        fillCounters: $fillCounters,
                        rawRowsCount: $rawRowsCount,
                    ),
                    'duplicate_license_numbers' => array_keys($duplicateLicenseNumbers),
                ],
            ]),
        ])->save();

        $this->batchLifecycleManager->refreshIssueCounters($batch);

        $this->logger->info('license_import.pipeline.validate_minimal.completed', [
            'batch_id' => $batch->getKey(),
            'source' => $context->source,
            'raw_rows_count' => $rawRowsCount,
            'valid_rows_count' => $validRowsCount,
            'invalid_rows_count' => $invalidRowsCount,
            'valid_ratio_percent' => $validRatioPercent,
            'invalid_ratio_percent' => $invalidRatioPercent,
            'has_blocking_failure' => $hasBlockingFailure,
        ]);

        if ($rawRowsCount < $minimumRawRowsCount || $hasBlockingFailure) {
            throw new MinimalValidationFailedException(
                'Minimal validation failed for the current import batch.'
            );
        }
    }

    /**
     * @param array<string, int> $fillCounters
     * @return array<string, int>
     */
    private function computeRequiredFieldFillRates(array $fillCounters, int $rawRowsCount): array
    {
        $rates = [];

        foreach ($fillCounters as $field => $filledRowsCount) {
            $rates[$field] = $rawRowsCount > 0
                ? (int) floor(($filledRowsCount / $rawRowsCount) * 100)
                : 0;
        }

        return $rates;
    }
}