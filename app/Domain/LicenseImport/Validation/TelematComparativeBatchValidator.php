<?php

declare(strict_types=1);

namespace App\Domain\LicenseImport\Validation;

use App\Domain\LicenseImport\DTO\LicenseImportExecutionContext;
use App\Domain\LicenseImport\Enums\IssueSeverity;
use App\Domain\LicenseImport\Pipeline\Exceptions\ComparativeValidationFailedException;
use App\Domain\LicenseImport\Pipeline\TelematBatchLifecycleManager;
use App\Domain\LicenseImport\Pipeline\TelematIssueRecorder;
use App\Domain\LicenseImport\Pipeline\TelematPipelineLogger;
use App\Models\LicenseImportBatch;
use RuntimeException;

final class TelematComparativeBatchValidator
{
    public function __construct(
        private readonly TelematIssueRecorder $issueRecorder,
        private readonly TelematBatchLifecycleManager $batchLifecycleManager,
        private readonly TelematPipelineLogger $logger,
    ) {
    }

    public function validate(LicenseImportBatch $batch, LicenseImportExecutionContext $context): void
    {
        $comparativeConfig = config('license_import.validation.comparative', []);

        if (!is_array($comparativeConfig)) {
            throw new RuntimeException('The license_import.validation.comparative config must be an array.');
        }

        $enabled = (bool) ($comparativeConfig['enabled'] ?? false);

        if ($enabled === false) {
            $this->logger->info('license_import.pipeline.validate_comparative.skipped_disabled', [
                'batch_id' => $batch->getKey(),
                'source' => $context->source,
            ]);

            $this->batchLifecycleManager->refreshIssueCounters($batch);

            return;
        }

        $previousBatch = LicenseImportBatch::query()
            ->forSource($context->source)
            ->active()
            ->whereKeyNot($batch->getKey())
            ->latestFirst()
            ->first();

        if ($previousBatch === null) {
            $this->logger->info('license_import.pipeline.validate_comparative.skipped_no_previous_batch', [
                'batch_id' => $batch->getKey(),
                'source' => $context->source,
            ]);

            $existingSummary = is_array($batch->summary) ? $batch->summary : [];

            $batch->forceFill([
                'summary' => array_replace_recursive($existingSummary, [
                    'comparative_validation' => [
                        'enabled' => true,
                        'skipped' => true,
                        'reason' => 'no_previous_active_batch',
                    ],
                ]),
            ])->save();

            $this->batchLifecycleManager->refreshIssueCounters($batch);

            return;
        }

        $currentRawRowsCount = (int) $batch->raw_rows_count;
        $currentValidRowsCount = (int) $batch->valid_rows_count;
        $currentInvalidRowsCount = (int) $batch->invalid_rows_count;

        $previousRawRowsCount = (int) $previousBatch->raw_rows_count;
        $previousValidRowsCount = (int) $previousBatch->valid_rows_count;
        $previousInvalidRowsCount = (int) $previousBatch->invalid_rows_count;

        $currentInvalidRatioPercent = $this->computeRatioPercent(
            numerator: $currentInvalidRowsCount,
            denominator: $currentRawRowsCount,
        );

        $previousInvalidRatioPercent = $this->computeRatioPercent(
            numerator: $previousInvalidRowsCount,
            denominator: $previousRawRowsCount,
        );

        $currentFillRates = $this->extractRequiredFieldFillRatesFromBatchSummary($batch);
        $previousFillRates = $this->extractRequiredFieldFillRatesFromBatchSummary($previousBatch);

        $blockingFailureDetected = false;

        $blockingFailureDetected = $this->evaluateComparativeMetric(
            batch: $batch,
            metricCodePrefix: 'comparative_total_rows_variation',
            metricLabel: 'total rows count',
            currentValue: $currentRawRowsCount,
            previousValue: $previousRawRowsCount,
            thresholds: is_array($comparativeConfig['total_rows_variation'] ?? null)
                ? $comparativeConfig['total_rows_variation']
                : [],
            blockingFailureDetected: $blockingFailureDetected,
        );

        $blockingFailureDetected = $this->evaluateComparativeMetric(
            batch: $batch,
            metricCodePrefix: 'comparative_valid_rows_variation',
            metricLabel: 'valid rows count',
            currentValue: $currentValidRowsCount,
            previousValue: $previousValidRowsCount,
            thresholds: is_array($comparativeConfig['valid_rows_variation'] ?? null)
                ? $comparativeConfig['valid_rows_variation']
                : [],
            blockingFailureDetected: $blockingFailureDetected,
        );

        $blockingFailureDetected = $this->evaluateComparativeMetric(
            batch: $batch,
            metricCodePrefix: 'comparative_invalid_ratio_variation',
            metricLabel: 'invalid ratio percent',
            currentValue: $currentInvalidRatioPercent,
            previousValue: $previousInvalidRatioPercent,
            thresholds: is_array($comparativeConfig['invalid_ratio_variation'] ?? null)
                ? $comparativeConfig['invalid_ratio_variation']
                : [],
            blockingFailureDetected: $blockingFailureDetected,
        );

        $fieldFillRateThresholds = is_array($comparativeConfig['field_fill_rate_variation'] ?? null)
            ? $comparativeConfig['field_fill_rate_variation']
            : [];

        foreach ($currentFillRates as $field => $currentRate) {
            if (!array_key_exists($field, $previousFillRates)) {
                continue;
            }

            $blockingFailureDetected = $this->evaluateComparativeMetric(
                batch: $batch,
                metricCodePrefix: sprintf('comparative_field_fill_rate_variation_%s', $field),
                metricLabel: sprintf('field fill rate "%s"', $field),
                currentValue: (int) $currentRate,
                previousValue: (int) $previousFillRates[$field],
                thresholds: $fieldFillRateThresholds,
                blockingFailureDetected: $blockingFailureDetected,
            );
        }

        $existingSummary = is_array($batch->summary) ? $batch->summary : [];

        $batch->forceFill([
            'summary' => array_replace_recursive($existingSummary, [
                'comparative_validation' => [
                    'enabled' => true,
                    'skipped' => false,
                    'previous_batch_id' => $previousBatch->getKey(),
                    'current' => [
                        'raw_rows_count' => $currentRawRowsCount,
                        'valid_rows_count' => $currentValidRowsCount,
                        'invalid_rows_count' => $currentInvalidRowsCount,
                        'invalid_ratio_percent' => $currentInvalidRatioPercent,
                        'required_field_fill_rates' => $currentFillRates,
                    ],
                    'previous' => [
                        'raw_rows_count' => $previousRawRowsCount,
                        'valid_rows_count' => $previousValidRowsCount,
                        'invalid_rows_count' => $previousInvalidRowsCount,
                        'invalid_ratio_percent' => $previousInvalidRatioPercent,
                        'required_field_fill_rates' => $previousFillRates,
                    ],
                ],
            ]),
        ])->save();

        $this->batchLifecycleManager->refreshIssueCounters($batch);

        $this->logger->info('license_import.pipeline.validate_comparative.completed', [
            'batch_id' => $batch->getKey(),
            'source' => $context->source,
            'previous_batch_id' => $previousBatch->getKey(),
            'blocking_failure_detected' => $blockingFailureDetected,
        ]);

        if ($blockingFailureDetected) {
            throw new ComparativeValidationFailedException(
                'Comparative validation failed for the current import batch.'
            );
        }
    }

    /**
     * @return array<string, int>
     */
    private function extractRequiredFieldFillRatesFromBatchSummary(LicenseImportBatch $batch): array
    {
        $summary = is_array($batch->summary) ? $batch->summary : [];
        $minimalValidation = is_array($summary['minimal_validation'] ?? null)
            ? $summary['minimal_validation']
            : [];

        $fillRates = is_array($minimalValidation['required_field_fill_rates'] ?? null)
            ? $minimalValidation['required_field_fill_rates']
            : [];

        $normalized = [];

        foreach ($fillRates as $field => $rate) {
            if (!is_string($field) || trim($field) === '') {
                continue;
            }

            $normalized[$field] = (int) $rate;
        }

        return $normalized;
    }

    private function computeRelativeVariationPercent(int $currentValue, int $previousValue): int
    {
        if ($previousValue === 0) {
            return $currentValue === 0 ? 0 : 100;
        }

        return (int) floor((abs($currentValue - $previousValue) / abs($previousValue)) * 100);
    }

    private function computeRatioPercent(int $numerator, int $denominator): int
    {
        if ($denominator <= 0) {
            return 0;
        }

        return (int) floor(($numerator / $denominator) * 100);
    }

    /**
     * @param array<string, mixed> $thresholds
     */
    private function evaluateComparativeMetric(
        LicenseImportBatch $batch,
        string $metricCodePrefix,
        string $metricLabel,
        int $currentValue,
        int $previousValue,
        array $thresholds,
        bool $blockingFailureDetected,
    ): bool {
        $warningPercent = (int) ($thresholds['warning_percent'] ?? 0);
        $rejectPercent = (int) ($thresholds['reject_percent'] ?? 100);

        $variationPercent = $this->computeRelativeVariationPercent(
            currentValue: $currentValue,
            previousValue: $previousValue,
        );

        if ($variationPercent >= $rejectPercent) {
            $this->issueRecorder->record(
                batch: $batch,
                severity: IssueSeverity::Error,
                code: $metricCodePrefix . '_reject',
                message: sprintf(
                    'Comparative variation for %s exceeds the reject threshold [%d%% >= %d%%].',
                    $metricLabel,
                    $variationPercent,
                    $rejectPercent,
                ),
                context: [
                    'metric' => $metricLabel,
                    'current_value' => $currentValue,
                    'previous_value' => $previousValue,
                    'variation_percent' => $variationPercent,
                    'warning_percent' => $warningPercent,
                    'reject_percent' => $rejectPercent,
                ],
            );

            return true;
        }

        if ($variationPercent >= $warningPercent) {
            $this->issueRecorder->record(
                batch: $batch,
                severity: IssueSeverity::Warning,
                code: $metricCodePrefix . '_warning',
                message: sprintf(
                    'Comparative variation for %s exceeds the warning threshold [%d%% >= %d%%].',
                    $metricLabel,
                    $variationPercent,
                    $warningPercent,
                ),
                context: [
                    'metric' => $metricLabel,
                    'current_value' => $currentValue,
                    'previous_value' => $previousValue,
                    'variation_percent' => $variationPercent,
                    'warning_percent' => $warningPercent,
                    'reject_percent' => $rejectPercent,
                ],
            );
        }

        return $blockingFailureDetected;
    }
}