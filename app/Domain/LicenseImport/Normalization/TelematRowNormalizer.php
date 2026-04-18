<?php

declare(strict_types=1);

namespace App\Domain\LicenseImport\Normalization;

use App\Domain\LicenseImport\DTO\LicenseImportExecutionContext;
use App\Domain\LicenseImport\Pipeline\TelematPipelineLogger;
use RuntimeException;

final class TelematRowNormalizer
{
    public function __construct(
        private readonly TelematHeaderNormalizer $headerNormalizer,
        private readonly TelematPipelineLogger $logger,
    ) {
    }

    /**
     * @param array{
     *     headers: array<int, string>,
     *     rows: array<int, array<string, string|null>>
     * } $parsedTable
     * @return array<int, array<string, mixed>>
     */
    public function normalize(array $parsedTable, LicenseImportExecutionContext $context): array
    {
        $headers = $parsedTable['headers'] ?? null;
        $rows = $parsedTable['rows'] ?? null;

        if (!is_array($headers) || !is_array($rows)) {
            throw new RuntimeException('The parsed table payload must contain "headers" and "rows" arrays.');
        }

        $mappingConfig = config('license_import.mapping', []);

        if (!is_array($mappingConfig)) {
            throw new RuntimeException('The license_import.mapping config must be an array.');
        }

        $fieldMappings = is_array($mappingConfig['fields'] ?? null)
            ? $mappingConfig['fields']
            : [];

        $sourceColumnMappings = is_array($mappingConfig['source_columns'] ?? null)
            ? $mappingConfig['source_columns']
            : [];

        $normalizedRows = [];

        foreach ($rows as $rowIndex => $sourceRow) {
            if (!is_array($sourceRow)) {
                continue;
            }

            $normalizedSourceRow = $this->normalizeSourceRowKeys($sourceRow);

            $mappedBusinessFields = $this->mapConfiguredFields(
                normalizedSourceRow: $normalizedSourceRow,
                fieldMappings: $fieldMappings,
            );

            $mappedSourceColumns = $this->mapConfiguredFields(
                normalizedSourceRow: $normalizedSourceRow,
                fieldMappings: $sourceColumnMappings,
            );

            $normalizedRows[] = array_merge(
                [
                    'row_index' => (int) $rowIndex,
                    'source_row' => $sourceRow,
                    'normalized_source_row' => $normalizedSourceRow,
                ],
                $mappedBusinessFields,
                $mappedSourceColumns,
            );
        }

        $this->logger->info('license_import.pipeline.normalize.completed', [
            'source' => $context->source,
            'headers_count' => count($headers),
            'rows_count' => count($normalizedRows),
            'mapped_business_fields' => array_keys($fieldMappings),
            'mapped_source_columns' => array_keys($sourceColumnMappings),
        ]);

        return $normalizedRows;
    }

    /**
     * @param array<string, string|null> $sourceRow
     * @return array<string, string|null>
     */
    private function normalizeSourceRowKeys(array $sourceRow): array
    {
        $normalized = [];

        foreach ($sourceRow as $rawHeader => $value) {
            if (!is_string($rawHeader) || trim($rawHeader) === '') {
                continue;
            }

            $normalizedHeader = $this->headerNormalizer->normalize($rawHeader);

            if ($normalizedHeader === '') {
                continue;
            }

            $normalizedValue = $this->normalizeCellValue($value);

            if (!array_key_exists($normalizedHeader, $normalized)) {
                $normalized[$normalizedHeader] = $normalizedValue;
                continue;
            }

            if ($this->isEmptyCellValue($normalized[$normalizedHeader]) && !$this->isEmptyCellValue($normalizedValue)) {
                $normalized[$normalizedHeader] = $normalizedValue;
            }
        }

        return $normalized;
    }

    /**
     * @param array<string, string|null> $normalizedSourceRow
     * @param array<string, mixed> $fieldMappings
     * @return array<string, string|null>
     */
    private function mapConfiguredFields(array $normalizedSourceRow, array $fieldMappings): array
    {
        $mapped = [];

        foreach ($fieldMappings as $targetField => $fieldConfig) {
            if (!is_string($targetField) || trim($targetField) === '') {
                continue;
            }

            if (!is_array($fieldConfig)) {
                $mapped[$targetField] = null;
                continue;
            }

            $aliases = $fieldConfig['sources'] ?? null;

            if (!is_array($aliases)) {
                $mapped[$targetField] = null;
                continue;
            }

            $mapped[$targetField] = $this->resolveMappedValueFromAliases(
                normalizedSourceRow: $normalizedSourceRow,
                aliases: $aliases,
            );
        }

        return $mapped;
    }

    /**
     * @param array<string, string|null> $normalizedSourceRow
     * @param array<int, mixed> $aliases
     */
    private function resolveMappedValueFromAliases(array $normalizedSourceRow, array $aliases): ?string
    {
        foreach ($aliases as $alias) {
            if (!is_string($alias) || trim($alias) === '') {
                continue;
            }

            $normalizedAlias = $this->headerNormalizer->normalize($alias);

            if ($normalizedAlias === '') {
                continue;
            }

            if (!array_key_exists($normalizedAlias, $normalizedSourceRow)) {
                continue;
            }

            $value = $normalizedSourceRow[$normalizedAlias];

            if ($this->isEmptyCellValue($value)) {
                continue;
            }

            return $value;
        }

        return null;
    }

    private function normalizeCellValue(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $normalized = trim(preg_replace('/\s+/u', ' ', $value) ?? '');

        return $normalized === '' ? null : $normalized;
    }

    private function isEmptyCellValue(?string $value): bool
    {
        return $value === null || trim($value) === '';
    }
}