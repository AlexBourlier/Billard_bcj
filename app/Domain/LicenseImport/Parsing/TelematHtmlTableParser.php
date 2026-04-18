<?php

declare(strict_types=1);

namespace App\Domain\LicenseImport\Parsing;

use App\Domain\LicenseImport\DTO\LicenseImportExecutionContext;
use App\Domain\LicenseImport\Pipeline\Exceptions\TelematHtmlParseException;
use App\Domain\LicenseImport\Pipeline\TelematPipelineLogger;
use App\Domain\LicenseImport\Normalization\TelematHeaderNormalizer;
use DOMDocument;
use DOMElement;
use DOMNode;
use DOMNodeList;
use DOMXPath;

final class TelematHtmlTableParser
{
    
    public function __construct(
        private readonly TelematHeaderNormalizer $headerNormalizer,
        private readonly TelematPipelineLogger $logger,
    ) {
    }
    

    /**
     * @return array{
     *     headers: array<int, string>,
     *     rows: array<int, array<string, string|null>>
     * }
     */
    public function parse(string $html, LicenseImportExecutionContext $context): array
    {
        $document = new DOMDocument();
        $previousUseInternalErrors = libxml_use_internal_errors(true);

        try {
            $loaded = $document->loadHTML(
                '<?xml encoding="UTF-8">' . $html,
                LIBXML_NOWARNING | LIBXML_NOERROR
            );

            if ($loaded === false) {
                throw new TelematHtmlParseException('Unable to load source HTML into DOMDocument.');
            }

            $xpath = new DOMXPath($document);
            $parsingConfig = config('license_import.parsing', []);

            if (!is_array($parsingConfig)) {
                throw new TelematHtmlParseException('The license_import.parsing config must be an array.');
            }

            $tableSelector = $parsingConfig['table_selector'] ?? null;
            $useHeaderDetectionFallback = (bool) ($parsingConfig['use_header_detection_fallback'] ?? true);
            $minimumDetectedRows = (int) ($parsingConfig['minimum_detected_rows'] ?? 1);

            $tableNode = null;

            if (is_string($tableSelector) && trim($tableSelector) !== '') {
                $tableNode = $this->findTableByConfiguredSelector($xpath, trim($tableSelector));

                if ($tableNode !== null) {
                    $this->logger->info('license_import.pipeline.parse.table_selected_by_config', [
                        'source' => $context->source,
                        'selector' => $tableSelector,
                    ]);
                } else {
                    $this->logger->info('license_import.pipeline.parse.table_selector_not_matched', [
                        'source' => $context->source,
                        'selector' => $tableSelector,
                    ]);
                }
            }

            if ($tableNode === null && $useHeaderDetectionFallback) {
                $tableNode = $this->findTableByHeaderDetection($xpath);

                if ($tableNode !== null) {
                    $this->logger->info('license_import.pipeline.parse.table_selected_by_header_detection', [
                        'source' => $context->source,
                    ]);
                }
            }

            if ($tableNode === null) {
                throw new TelematHtmlParseException('No suitable HTML table could be found in the source document.');
            }

            $headers = $this->extractTableHeaders($xpath, $tableNode);

            if ($headers === []) {
                throw new TelematHtmlParseException('The selected HTML table does not contain exploitable headers.');
            }

            $rows = $this->extractAssociativeRowsFromTable($xpath, $tableNode, $headers);

            if (count($rows) < $minimumDetectedRows) {
                throw new TelematHtmlParseException(sprintf(
                    'The selected HTML table contains fewer rows than expected [%d < %d].',
                    count($rows),
                    $minimumDetectedRows,
                ));
            }

            $this->logger->info('license_import.pipeline.parse.completed', [
                'source' => $context->source,
                'headers_count' => count($headers),
                'rows_count' => count($rows),
                'headers' => $headers,
            ]);

            return [
                'headers' => $headers,
                'rows' => $rows,
            ];
        } finally {
            libxml_clear_errors();
            libxml_use_internal_errors($previousUseInternalErrors);
        }
    }

    private function findTableByConfiguredSelector(DOMXPath $xpath, string $selector): ?DOMElement
    {
        if (str_starts_with($selector, '/') || str_starts_with($selector, '(')) {
            $nodes = $xpath->query($selector);

            if ($nodes === false || $nodes->length === 0) {
                return null;
            }

            foreach ($nodes as $node) {
                if ($node instanceof DOMElement && strtolower($node->tagName) === 'table') {
                    return $node;
                }
            }

            return null;
        }

        if (str_starts_with($selector, '#')) {
            $id = substr($selector, 1);

            if ($id === '') {
                return null;
            }

            $query = sprintf('//table[@id=%s]', $this->buildXpathLiteral($id));
            $nodes = $xpath->query($query);

            if ($nodes === false || $nodes->length === 0) {
                return null;
            }

            $node = $nodes->item(0);

            return $node instanceof DOMElement ? $node : null;
        }

        return null;
    }

    private function findTableByHeaderDetection(DOMXPath $xpath): ?DOMElement
    {
        $mappingConfig = config('license_import.mapping', []);
        $requiredFields = is_array($mappingConfig['required_fields'] ?? null)
            ? $mappingConfig['required_fields']
            : [];
        $fieldMappings = is_array($mappingConfig['fields'] ?? null)
            ? $mappingConfig['fields']
            : [];

        $tables = $xpath->query('//table');

        if ($tables === false || $tables->length === 0) {
            return null;
        }

        $bestTable = null;
        $bestScore = -1;

        foreach ($tables as $table) {
            if (!$table instanceof DOMElement) {
                continue;
            }

            $headers = $this->extractTableHeaders($xpath, $table);

            if ($headers === []) {
                continue;
            }

            $normalizedHeaders = array_map(
                fn (string $header): string => $this->headerNormalizer->normalize($header),
                $headers,
            );

            $matchedRequiredFields = 0;

            foreach ($requiredFields as $field) {
                if (!is_string($field) || $field === '') {
                    continue;
                }

                $aliases = $this->extractFieldSourceAliases($fieldMappings, $field);
                $normalizedAliases = array_map(
                    fn (string $alias): string => $this->headerNormalizer->normalize($alias),
                    $aliases,
                );

                if ($this->hasAnyCommonValue($normalizedHeaders, $normalizedAliases)) {
                    $matchedRequiredFields++;
                }
            }

            if ($matchedRequiredFields > $bestScore) {
                $bestScore = $matchedRequiredFields;
                $bestTable = $table;
            }
        }

        return $bestScore > 0 ? $bestTable : null;
    }

    /**
     * @return array<int, string>
     */
    private function extractTableHeaders(DOMXPath $xpath, DOMElement $table): array
    {
        $headerCells = $xpath->query('./thead/tr[1]/th', $table);

        if ($headerCells !== false && $headerCells->length > 0) {
            return $this->extractNonEmptyCellTexts($headerCells);
        }

        $headerCells = $xpath->query('./tr[1]/th', $table);

        if ($headerCells !== false && $headerCells->length > 0) {
            return $this->extractNonEmptyCellTexts($headerCells);
        }

        $headerCells = $xpath->query('./tbody/tr[1]/th', $table);

        if ($headerCells !== false && $headerCells->length > 0) {
            return $this->extractNonEmptyCellTexts($headerCells);
        }

        $firstRowCells = $xpath->query('./tr[1]/td', $table);

        if ($firstRowCells !== false && $firstRowCells->length > 0) {
            return $this->extractNonEmptyCellTexts($firstRowCells);
        }

        $firstRowCells = $xpath->query('./tbody/tr[1]/td', $table);

        if ($firstRowCells !== false && $firstRowCells->length > 0) {
            return $this->extractNonEmptyCellTexts($firstRowCells);
        }

        return [];
    }

    /**
     * @param array<int, string> $headers
     * @return array<int, array<string, string|null>>
     */
    private function extractAssociativeRowsFromTable(
        DOMXPath $xpath,
        DOMElement $table,
        array $headers,
    ): array {
        $rows = [];
        $rowNodes = $xpath->query('./thead/tr | ./tbody/tr | ./tfoot/tr | ./tr', $table);

        if ($rowNodes === false || $rowNodes->length === 0) {
            return $rows;
        }

        $headerSignature = $this->buildHeaderSignature($headers);
        $headerCount = count($headers);

        foreach ($rowNodes as $rowNode) {
            if (!$rowNode instanceof DOMElement) {
                continue;
            }

            $cellNodes = $xpath->query('./th|./td', $rowNode);

            if ($cellNodes === false || $cellNodes->length === 0) {
                continue;
            }

            $cellTexts = $this->extractCellTexts($cellNodes);

            if ($cellTexts === []) {
                continue;
            }

            $comparableCellTexts = $cellTexts;

            if (count($comparableCellTexts) < $headerCount) {
                $comparableCellTexts = array_pad($comparableCellTexts, $headerCount, null);
            } elseif (count($comparableCellTexts) > $headerCount) {
                $comparableCellTexts = array_slice($comparableCellTexts, 0, $headerCount);
            }

            $currentSignature = $this->buildHeaderSignature($comparableCellTexts);

            if ($currentSignature === $headerSignature) {
                continue;
            }

            $rowHasOnlyHeaderCells = true;

            foreach ($cellNodes as $cellNode) {
                if ($cellNode instanceof DOMElement && strtolower($cellNode->tagName) !== 'th') {
                    $rowHasOnlyHeaderCells = false;
                    break;
                }
            }

            if ($rowHasOnlyHeaderCells) {
                continue;
            }

            $associativeRow = [];

            foreach ($headers as $index => $header) {
                $associativeRow[$header] = $comparableCellTexts[$index] ?? null;
            }

            if ($this->isAssociativeRowEmpty($associativeRow)) {
                continue;
            }

            $rows[] = $associativeRow;
        }

        return $rows;
    }

    /**
     * @param DOMNodeList<DOMNode> $nodes
     * @return array<int, string>
     */
    private function extractNonEmptyCellTexts(DOMNodeList $nodes): array
    {
        return array_values(array_filter(
            $this->extractCellTexts($nodes),
            static fn (?string $value): bool => $value !== null && $value !== '',
        ));
    }

    /**
     * @param DOMNodeList<DOMNode> $nodes
     * @return array<int, string|null>
     */
    private function extractCellTexts(DOMNodeList $nodes): array
    {
        $values = [];

        foreach ($nodes as $node) {
            $text = trim(preg_replace('/\s+/u', ' ', $node->textContent ?? '') ?? '');
            $values[] = $text === '' ? null : $text;
        }

        return $values;
    }

    /**
     * @param array<string, mixed> $fieldMappings
     * @return array<int, string>
     */
    private function extractFieldSourceAliases(array $fieldMappings, string $field): array
    {
        $fieldConfig = $fieldMappings[$field] ?? null;

        if (!is_array($fieldConfig)) {
            return [];
        }

        $sources = $fieldConfig['sources'] ?? null;

        if (!is_array($sources)) {
            return [];
        }

        return array_values(array_filter(
            $sources,
            static fn ($value): bool => is_string($value) && trim($value) !== '',
        ));
    }

    private function hasAnyCommonValue(array $left, array $right): bool
    {
        foreach ($left as $leftValue) {
            if (!is_string($leftValue) || $leftValue === '') {
                continue;
            }

            if (in_array($leftValue, $right, true)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array<int, string|null> $values
     */
    private function buildHeaderSignature(array $values): string
    {
        $normalized = array_map(
            fn (?string $value): string => $value === null ? '' : $this->headerNormalizer->normalize($value),
            $values,
        );

        return implode('|', $normalized);
    }

    /**
     * @param array<string, string|null> $row
     */
    private function isAssociativeRowEmpty(array $row): bool
    {
        foreach ($row as $value) {
            if ($value !== null && trim($value) !== '') {
                return false;
            }
        }

        return true;
    }

    private function buildXpathLiteral(string $value): string
    {
        if (!str_contains($value, "'")) {
            return "'" . $value . "'";
        }

        if (!str_contains($value, '"')) {
            return '"' . $value . '"';
        }

        $parts = explode("'", $value);
        $segments = [];

        foreach ($parts as $index => $part) {
            if ($part !== '') {
                $segments[] = "'" . $part . "'";
            }

            if ($index < count($parts) - 1) {
                $segments[] = "\"'\"";
            }
        }

        return 'concat(' . implode(', ', $segments) . ')';
    }
}