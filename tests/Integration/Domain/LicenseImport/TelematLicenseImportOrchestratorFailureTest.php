<?php

declare(strict_types=1);

namespace Tests\Integration\Domain\LicenseImport;

use App\Domain\LicenseImport\Enums\IssueSeverity;
use App\Domain\LicenseImport\Fetch\Contracts\TelematFetcherInterface;
use App\Domain\LicenseImport\Fetch\Exceptions\TelematInvalidResponseException;
use App\Domain\LicenseImport\Fetch\Exceptions\TelematNetworkException;
use App\Domain\LicenseImport\Fetch\TelematFetchConfig;
use App\Domain\LicenseImport\Fetch\TelematFetchResult;
use App\Domain\LicenseImport\Pipeline\Exceptions\ComparativeValidationFailedException;
use App\Domain\LicenseImport\Pipeline\Exceptions\MinimalValidationFailedException;
use App\Domain\LicenseImport\Pipeline\Exceptions\TelematHtmlParseException;
use App\Domain\LicenseImport\Pipeline\TelematLicenseImportOrchestrator;
use App\Models\LicenseImportBatch;
use DateTimeImmutable;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Tests\Integration\Domain\LicenseImport\Concerns\InteractsWithTelematHtmlFixtures;

final class TelematLicenseImportOrchestratorFailureTest extends TelematLicenseImportOrchestratorTestCase
{
    use InteractsWithTelematHtmlFixtures;

    public function test_it_fails_when_html_table_cannot_be_found(): void
    {
        $previousBatch = $this->createActivePreviousBatch();

        $this->swapTelematFetcher(new class implements TelematFetcherInterface {
            public function fetch(TelematFetchConfig $config): TelematFetchResult
            {
                return new TelematFetchResult(
                    html: <<<HTML
                    <html>
                    <body>
                        <div class="content">
                            <h1>Licences</h1>
                            <p>Aucune table exploitable dans cette réponse.</p>
                        </div>
                    </body>
                    </html>
                    HTML,
                    httpStatus: 200,
                    contentType: 'text/html; charset=UTF-8',
                    finalUrl: 'https://example.test/licenses',
                    fetchedAt: new DateTimeImmutable(),
                );
            }
        });

        $context = $this->makeExecutionContext(dryRun: false);

        /** @var TelematLicenseImportOrchestrator $orchestrator */
        $orchestrator = app(TelematLicenseImportOrchestrator::class);

        $this->expectException(TelematHtmlParseException::class);
        $this->expectExceptionMessage('No suitable HTML table could be found in the source document.');

        try {
            $orchestrator->run($context);
        } finally {
            $failedBatch = $this->assertLatestFailedBatchCreatedFromPrevious(
                previousBatch: $previousBatch,
                expectedFailureStage: 'parse',
                expectedFailureCode: 'parse_failed',
                expectedFailureMessage: 'No suitable HTML table could be found in the source document.',
                notNullMessage: 'Un nouveau batch devrait avoir été créé avant l’échec.',
                notSameMessage: 'Le batch en échec doit être distinct du batch précédent.',
            );

            $this->assertSame(0, $failedBatch->raw_rows_count);
            $this->assertSame(0, $failedBatch->valid_rows_count);
            $this->assertSame(0, $failedBatch->invalid_rows_count);
            $this->assertSame(1, $failedBatch->error_count);
            $this->assertSame(0, $failedBatch->warning_count);

            $this->assertCount(
                0,
                $failedBatch->snapshots()->get(),
                'Aucun snapshot ne doit être persisté si aucune table exploitable n’est trouvée.'
            );

            $issues = $failedBatch->issues()->get();

            $this->assertCount(1, $issues, 'Une issue structurée doit être enregistrée pour cet échec.');
            $this->assertSame(IssueSeverity::Error, $issues[0]->severity);
            $this->assertSame('parse_failed', $issues[0]->code);
            $this->assertSame(
                'No suitable HTML table could be found in the source document.',
                $issues[0]->message
            );
            $this->assertNull($issues[0]->row_index);
        }
    }

    public function test_it_fails_when_fetch_returns_an_invalid_response(): void
    {
        $previousBatch = $this->createActivePreviousBatch();

        Http::fake([
            '*' => Http::response(
                '{"ok":false}',
                200,
                ['Content-Type' => 'application/json']
            ),
        ]);

        $context = $this->makeExecutionContext(dryRun: false);

        /** @var TelematLicenseImportOrchestrator $orchestrator */
        $orchestrator = app(TelematLicenseImportOrchestrator::class);

        $this->expectException(TelematInvalidResponseException::class);
        $this->expectExceptionMessage('Telemat response Content-Type [application/json] is not in the expected list.');

        try {
            $orchestrator->run($context);
        } finally {
            $failedBatch = $this->assertLatestFailedBatchCreatedFromPrevious(
                previousBatch: $previousBatch,
                expectedFailureStage: 'fetch',
                expectedFailureCode: 'fetch_invalid_response',
                expectedFailureMessage: 'Telemat response Content-Type [application/json] is not in the expected list.',
                notNullMessage: 'Un nouveau batch devrait avoir été créé avant l’échec de fetch.',
                notSameMessage: 'Le batch en échec doit être distinct du batch précédent.',
            );

            $this->assertSame(0, $failedBatch->raw_rows_count);
            $this->assertSame(0, $failedBatch->valid_rows_count);
            $this->assertSame(0, $failedBatch->invalid_rows_count);
            $this->assertSame(1, $failedBatch->error_count);
            $this->assertSame(0, $failedBatch->warning_count);

            $this->assertCount(0, $failedBatch->snapshots()->get());

            $issues = $failedBatch->issues()->get();

            $this->assertCount(1, $issues);
            $this->assertSame(IssueSeverity::Error, $issues[0]->severity);
            $this->assertSame('fetch_invalid_response', $issues[0]->code);
            $this->assertSame('Telemat response Content-Type [application/json] is not in the expected list.', $issues[0]->message);
            $this->assertNull($issues[0]->row_index);

            $issueContext = is_array($issues[0]->context) ? $issues[0]->context : [];

            $this->assertSame(TelematInvalidResponseException::class, $issueContext['exception_class'] ?? null);
            $this->assertSame('ffbi_telemat', $issueContext['source'] ?? null);
            $this->assertSame('manual', $issueContext['trigger_type'] ?? null);
            $this->assertSame('phpunit-license-import-test', $issueContext['triggered_by'] ?? null);
            $this->assertFalse($issueContext['dry_run'] ?? true);
            $this->assertSame('unexpected_content_type', $issueContext['reason'] ?? null);
            $this->assertSame(
                [
                    'http_status' => 200,
                    'final_url' => 'https://example.test/licenses',
                    'content_type' => 'application/json',
                    'expected_content_types' => [
                        'text/html',
                        'application/xhtml+xml',
                        'text/plain',
                    ],
                ],
                $issueContext['response_context'] ?? null
            );
        }
    }

    public function test_it_fails_when_minimal_validation_detects_missing_required_fields_and_valid_ratio_drops_below_threshold(): void
    {
        $previousBatch = $this->createActivePreviousBatch();

        $this->fakeSuccessfulHtmlResponse(
            $this->telematHtmlWithMissingRequiredFirstNameOnSecondRow()
        );

        $context = $this->makeExecutionContext(dryRun: false);

        /** @var TelematLicenseImportOrchestrator $orchestrator */
        $orchestrator = app(TelematLicenseImportOrchestrator::class);

        $this->expectException(MinimalValidationFailedException::class);
        $this->expectExceptionMessage('Minimal validation failed for the current import batch.');

        try {
            $orchestrator->run($context);
        } finally {
            $failedBatch = $this->assertLatestFailedBatchCreatedFromPrevious(
                previousBatch: $previousBatch,
                expectedFailureStage: 'minimal_validation',
                expectedFailureCode: 'minimal_validation_failed',
                expectedFailureMessage: 'Minimal validation failed for the current import batch.',
            );

            $summary = is_array($failedBatch->summary) ? $failedBatch->summary : [];

            $this->assertSame(2, $failedBatch->raw_rows_count);
            $this->assertSame(1, $failedBatch->valid_rows_count);
            $this->assertSame(1, $failedBatch->invalid_rows_count);
            $this->assertSame(4, $failedBatch->error_count);
            $this->assertSame(1, $failedBatch->warning_count);

            $snapshots = $failedBatch->snapshots()->orderBy('row_index')->get();

            $this->assertCount(2, $snapshots, 'Les snapshots doivent être conservés même si la validation minimale échoue.');

            $this->assertTrue((bool) $snapshots[0]->is_valid, 'La première ligne doit rester valide.');
            $this->assertFalse((bool) $snapshots[1]->is_valid, 'La seconde ligne doit être invalide.');

            $this->assertSame('191100 S', $snapshots[0]->license_number);
            $this->assertSame('ARROUAS', $snapshots[0]->last_name);
            $this->assertSame('ISABELLE', $snapshots[0]->first_name);
            $this->assertSame('Decouverte', $snapshots[0]->category);

            $this->assertSame('190399 F', $snapshots[1]->license_number);
            $this->assertSame('AUCHART', $snapshots[1]->last_name);
            $this->assertNull($snapshots[1]->first_name, 'Le prénom mappé doit être null pour la ligne invalide.');
            $this->assertSame('Decouverte', $snapshots[1]->category);

            $validationFlags = is_array($snapshots[1]->validation_flags) ? $snapshots[1]->validation_flags : [];

            $this->assertContains(
                'missing_required_field:first_name',
                $validationFlags,
                'Le snapshot invalide doit porter le flag du champ requis manquant.'
            );

            $issues = $failedBatch->issues()->orderBy('id')->get();

            $this->assertCount(
                5,
                $issues,
                'Le batch doit contenir 5 issues : missing_required_field, minimum_valid_ratio_not_reached, maximum_invalid_ratio_exceeded, required_field_fill_rate_below_threshold et minimal_validation_failed.'
            );

            $missingRequiredFieldIssue = $failedBatch->issues()->where('code', 'missing_required_field')->first();
            $minimumValidRatioIssue = $failedBatch->issues()->where('code', 'minimum_valid_ratio_not_reached')->first();
            $maximumInvalidRatioIssue = $failedBatch->issues()->where('code', 'maximum_invalid_ratio_exceeded')->first();
            $fillRateIssue = $failedBatch->issues()->where('code', 'required_field_fill_rate_below_threshold')->first();
            $minimalValidationFailureIssue = $failedBatch->issues()->where('code', 'minimal_validation_failed')->first();

            $this->assertNotNull($missingRequiredFieldIssue);
            $this->assertNotNull($minimumValidRatioIssue);
            $this->assertNotNull($maximumInvalidRatioIssue);
            $this->assertNotNull($fillRateIssue);
            $this->assertNotNull($minimalValidationFailureIssue);

            $this->assertSame(IssueSeverity::Warning, $missingRequiredFieldIssue->severity);
            $this->assertSame(1, $missingRequiredFieldIssue->row_index);
            $this->assertStringContainsString('first_name', $missingRequiredFieldIssue->message);

            $this->assertSame(IssueSeverity::Error, $minimumValidRatioIssue->severity);
            $this->assertNull($minimumValidRatioIssue->row_index);

            $minimumValidRatioContext = is_array($minimumValidRatioIssue->context) ? $minimumValidRatioIssue->context : [];
            $this->assertSame(1, $minimumValidRatioContext['valid_rows_count'] ?? null);
            $this->assertSame(2, $minimumValidRatioContext['raw_rows_count'] ?? null);
            $this->assertSame(50, $minimumValidRatioContext['valid_ratio_percent'] ?? null);
            $this->assertSame(100, $minimumValidRatioContext['minimum_valid_ratio_percent'] ?? null);

            $this->assertSame(IssueSeverity::Error, $maximumInvalidRatioIssue->severity);
            $this->assertNull($maximumInvalidRatioIssue->row_index);

            $maximumInvalidRatioContext = is_array($maximumInvalidRatioIssue->context) ? $maximumInvalidRatioIssue->context : [];
            $this->assertSame(1, $maximumInvalidRatioContext['invalid_rows_count'] ?? null);
            $this->assertSame(2, $maximumInvalidRatioContext['raw_rows_count'] ?? null);
            $this->assertSame(50, $maximumInvalidRatioContext['invalid_ratio_percent'] ?? null);
            $this->assertSame(0, $maximumInvalidRatioContext['maximum_invalid_ratio_percent'] ?? null);

            $this->assertSame(IssueSeverity::Error, $fillRateIssue->severity);
            $this->assertNull($fillRateIssue->row_index);

            $fillRateContext = is_array($fillRateIssue->context) ? $fillRateIssue->context : [];
            $this->assertSame('first_name', $fillRateContext['field'] ?? null);
            $this->assertSame(1, $fillRateContext['filled_rows_count'] ?? null);
            $this->assertSame(2, $fillRateContext['raw_rows_count'] ?? null);
            $this->assertSame(50, $fillRateContext['actual_fill_rate_percent'] ?? null);
            $this->assertSame(100, $fillRateContext['minimum_fill_rate_percent'] ?? null);

            $this->assertSame(IssueSeverity::Error, $minimalValidationFailureIssue->severity);
            $this->assertNull($minimalValidationFailureIssue->row_index);
            $this->assertSame(
                'Minimal validation failed for the current import batch.',
                $minimalValidationFailureIssue->message
            );

            $minimalValidationSummary = is_array($summary['minimal_validation'] ?? null)
                ? $summary['minimal_validation']
                : [];

            $this->assertSame(2, $minimalValidationSummary['raw_rows_count'] ?? null);
            $this->assertSame(1, $minimalValidationSummary['valid_rows_count'] ?? null);
            $this->assertSame(1, $minimalValidationSummary['invalid_rows_count'] ?? null);
            $this->assertSame(50, $minimalValidationSummary['valid_ratio_percent'] ?? null);
            $this->assertSame(50, $minimalValidationSummary['invalid_ratio_percent'] ?? null);

            $requiredFieldFillRates = is_array($minimalValidationSummary['required_field_fill_rates'] ?? null)
                ? $minimalValidationSummary['required_field_fill_rates']
                : [];

            $this->assertSame(50, $requiredFieldFillRates['first_name'] ?? null);
        }
    }

    public function test_it_fails_when_minimal_validation_rejects_duplicate_license_numbers(): void
    {
        $previousBatch = $this->createActivePreviousBatch();

        $this->fakeSuccessfulHtmlResponse(
            $this->telematHtmlWithDuplicateLicenseNumbers()
        );

        $context = $this->makeExecutionContext(dryRun: false);

        /** @var TelematLicenseImportOrchestrator $orchestrator */
        $orchestrator = app(TelematLicenseImportOrchestrator::class);

        $this->expectException(MinimalValidationFailedException::class);
        $this->expectExceptionMessage('Minimal validation failed for the current import batch.');

        try {
            $orchestrator->run($context);
        } finally {
            /** @var LicenseImportBatch|null $failedBatch */
            $failedBatch = LicenseImportBatch::query()
                ->where('source', 'ffbi_telemat')
                ->latest('id')
                ->first();

            $this->assertNotNull($failedBatch);
            $this->assertNotSame($previousBatch->id, $failedBatch->id);

            $this->assertSame('ffbi_telemat', $failedBatch->source);

            $this->assertBatchFailed(
                $failedBatch,
                expectedFailureStage: 'minimal_validation',
                expectedFailureCode: 'minimal_validation_failed',
                expectedFailureMessage: 'Minimal validation failed for the current import batch.',
            );

            $summary = is_array($failedBatch->summary) ? $failedBatch->summary : [];

            $this->assertSame(2, $failedBatch->raw_rows_count);
            $this->assertSame(0, $failedBatch->valid_rows_count);
            $this->assertSame(2, $failedBatch->invalid_rows_count);
            $this->assertSame(4, $failedBatch->error_count);
            $this->assertSame(0, $failedBatch->warning_count);

            $snapshots = $failedBatch->snapshots()->orderBy('row_index')->get();

            $this->assertCount(2, $snapshots);
            $this->assertFalse((bool) $snapshots[0]->is_valid);
            $this->assertFalse((bool) $snapshots[1]->is_valid);

            $this->assertSame('191100 S', $snapshots[0]->license_number);
            $this->assertSame('191100 S', $snapshots[1]->license_number);

            $validationFlagsFirst = is_array($snapshots[0]->validation_flags) ? $snapshots[0]->validation_flags : [];
            $validationFlagsSecond = is_array($snapshots[1]->validation_flags) ? $snapshots[1]->validation_flags : [];

            $this->assertContains('duplicate_license_number', $validationFlagsFirst);
            $this->assertContains('duplicate_license_number', $validationFlagsSecond);

            $issues = $failedBatch->issues()->orderBy('id')->get();

            $this->assertCount(
                4,
                $issues,
                'Le batch doit contenir 4 issues : duplicate_license_number_rejected, minimum_valid_ratio_not_reached, maximum_invalid_ratio_exceeded et minimal_validation_failed.'
            );

            $duplicateIssue = $failedBatch->issues()->where('code', 'duplicate_license_number_rejected')->first();
            $minimumValidRatioIssue = $failedBatch->issues()->where('code', 'minimum_valid_ratio_not_reached')->first();
            $maximumInvalidRatioIssue = $failedBatch->issues()->where('code', 'maximum_invalid_ratio_exceeded')->first();
            $minimalValidationFailureIssue = $failedBatch->issues()->where('code', 'minimal_validation_failed')->first();

            $this->assertNotNull($duplicateIssue);
            $this->assertNotNull($minimumValidRatioIssue);
            $this->assertNotNull($maximumInvalidRatioIssue);
            $this->assertNotNull($minimalValidationFailureIssue);

            $this->assertSame(IssueSeverity::Error, $duplicateIssue->severity);
            $this->assertNull($duplicateIssue->row_index);

            $duplicateContext = is_array($duplicateIssue->context) ? $duplicateIssue->context : [];
            $this->assertSame('191100 S', $duplicateContext['license_number'] ?? null);
            $this->assertSame([0, 1], $duplicateContext['row_indexes'] ?? null);
            $this->assertSame('reject', $duplicateContext['policy'] ?? null);

            $this->assertSame(IssueSeverity::Error, $minimumValidRatioIssue->severity);
            $this->assertNull($minimumValidRatioIssue->row_index);

            $minimumValidRatioContext = is_array($minimumValidRatioIssue->context) ? $minimumValidRatioIssue->context : [];
            $this->assertSame(0, $minimumValidRatioContext['valid_rows_count'] ?? null);
            $this->assertSame(2, $minimumValidRatioContext['raw_rows_count'] ?? null);
            $this->assertSame(0, $minimumValidRatioContext['valid_ratio_percent'] ?? null);
            $this->assertSame(100, $minimumValidRatioContext['minimum_valid_ratio_percent'] ?? null);

            $this->assertSame(IssueSeverity::Error, $maximumInvalidRatioIssue->severity);
            $this->assertNull($maximumInvalidRatioIssue->row_index);

            $maximumInvalidRatioContext = is_array($maximumInvalidRatioIssue->context) ? $maximumInvalidRatioIssue->context : [];
            $this->assertSame(2, $maximumInvalidRatioContext['invalid_rows_count'] ?? null);
            $this->assertSame(2, $maximumInvalidRatioContext['raw_rows_count'] ?? null);
            $this->assertSame(100, $maximumInvalidRatioContext['invalid_ratio_percent'] ?? null);
            $this->assertSame(0, $maximumInvalidRatioContext['maximum_invalid_ratio_percent'] ?? null);

            $this->assertSame(IssueSeverity::Error, $minimalValidationFailureIssue->severity);
            $this->assertNull($minimalValidationFailureIssue->row_index);
            $this->assertSame(
                'Minimal validation failed for the current import batch.',
                $minimalValidationFailureIssue->message
            );

            $minimalValidationSummary = is_array($summary['minimal_validation'] ?? null)
                ? $summary['minimal_validation']
                : [];

            $this->assertSame(2, $minimalValidationSummary['raw_rows_count'] ?? null);
            $this->assertSame(0, $minimalValidationSummary['valid_rows_count'] ?? null);
            $this->assertSame(2, $minimalValidationSummary['invalid_rows_count'] ?? null);
            $this->assertSame(0, $minimalValidationSummary['valid_ratio_percent'] ?? null);
            $this->assertSame(100, $minimalValidationSummary['invalid_ratio_percent'] ?? null);
            $this->assertSame(['191100 S'], $minimalValidationSummary['duplicate_license_numbers'] ?? null);

            $this->assertPreviousBatchRemainsActive($previousBatch);
        }
    }

    public function test_it_fails_when_minimal_validation_detects_raw_rows_below_threshold(): void
    {
        config()->set('license_import.validation.minimal.minimum_raw_rows_count', 2);

        $previousBatch = $this->createActivePreviousBatch();

        $this->fakeSuccessfulHtmlResponse(
            $this->telematHtmlWithSingleValidRow()
        );

        $context = $this->makeExecutionContext(dryRun: false);

        /** @var TelematLicenseImportOrchestrator $orchestrator */
        $orchestrator = app(TelematLicenseImportOrchestrator::class);

        $this->expectException(MinimalValidationFailedException::class);
        $this->expectExceptionMessage('Minimal validation failed for the current import batch.');

        try {
            $orchestrator->run($context);
        } finally {
            /** @var LicenseImportBatch|null $failedBatch */
            $failedBatch = LicenseImportBatch::query()
                ->where('source', 'ffbi_telemat')
                ->latest('id')
                ->first();

            $this->assertNotNull($failedBatch);
            $this->assertNotSame($previousBatch->id, $failedBatch->id);

            $this->assertSame('ffbi_telemat', $failedBatch->source);

            $this->assertBatchFailed(
                $failedBatch,
                expectedFailureStage: 'minimal_validation',
                expectedFailureCode: 'minimal_validation_failed',
                expectedFailureMessage: 'Minimal validation failed for the current import batch.',
            );

            $summary = is_array($failedBatch->summary) ? $failedBatch->summary : [];

            $this->assertSame(1, $failedBatch->raw_rows_count);
            $this->assertSame(1, $failedBatch->valid_rows_count);
            $this->assertSame(0, $failedBatch->invalid_rows_count);
            $this->assertSame(2, $failedBatch->error_count);
            $this->assertSame(0, $failedBatch->warning_count);

            $snapshots = $failedBatch->snapshots()->orderBy('row_index')->get();

            $this->assertCount(1, $snapshots);
            $this->assertTrue((bool) $snapshots[0]->is_valid);

            $this->assertSame('191100 S', $snapshots[0]->license_number);
            $this->assertSame('ARROUAS', $snapshots[0]->last_name);
            $this->assertSame('ISABELLE', $snapshots[0]->first_name);
            $this->assertSame('Decouverte', $snapshots[0]->category);

            $issues = $failedBatch->issues()->orderBy('id')->get();

            $this->assertCount(
                2,
                $issues,
                'Le batch doit contenir 2 issues : minimal_raw_rows_below_threshold et minimal_validation_failed.'
            );

            $rawRowsIssue = $failedBatch->issues()->where('code', 'minimal_raw_rows_below_threshold')->first();
            $minimalValidationFailureIssue = $failedBatch->issues()->where('code', 'minimal_validation_failed')->first();

            $this->assertNotNull($rawRowsIssue);
            $this->assertNotNull($minimalValidationFailureIssue);

            $this->assertSame(IssueSeverity::Error, $rawRowsIssue->severity);
            $this->assertNull($rawRowsIssue->row_index);

            $rawRowsContext = is_array($rawRowsIssue->context) ? $rawRowsIssue->context : [];
            $this->assertSame(1, $rawRowsContext['raw_rows_count'] ?? null);
            $this->assertSame(2, $rawRowsContext['minimum_raw_rows_count'] ?? null);
            $this->assertSame('ffbi_telemat', $rawRowsContext['source'] ?? null);

            $this->assertSame(IssueSeverity::Error, $minimalValidationFailureIssue->severity);
            $this->assertNull($minimalValidationFailureIssue->row_index);
            $this->assertSame(
                'Minimal validation failed for the current import batch.',
                $minimalValidationFailureIssue->message
            );

            $minimalValidationSummary = is_array($summary['minimal_validation'] ?? null)
                ? $summary['minimal_validation']
                : [];

            $this->assertSame(1, $minimalValidationSummary['raw_rows_count'] ?? null);
            $this->assertSame(1, $minimalValidationSummary['valid_rows_count'] ?? null);
            $this->assertSame(0, $minimalValidationSummary['invalid_rows_count'] ?? null);
            $this->assertSame(100, $minimalValidationSummary['valid_ratio_percent'] ?? null);
            $this->assertSame(0, $minimalValidationSummary['invalid_ratio_percent'] ?? null);
            $this->assertSame([], $minimalValidationSummary['duplicate_license_numbers'] ?? null);

            $this->assertPreviousBatchRemainsActive($previousBatch);
        }
    }

    public function test_it_fails_when_comparative_validation_detects_total_rows_variation_above_reject_threshold(): void
    {
        config()->set('license_import.validation.minimal.minimum_raw_rows_count', 1);
        config()->set('license_import.validation.comparative.enabled', true);
        config()->set('license_import.validation.comparative.total_rows_variation', [
            'warning_percent' => 25,
            'reject_percent' => 40,
        ]);

        config()->set('license_import.validation.comparative.valid_rows_variation', [
            'warning_percent' => 101,
            'reject_percent' => 101,
        ]);
        config()->set('license_import.validation.comparative.invalid_ratio_variation', [
            'warning_percent' => 101,
            'reject_percent' => 101,
        ]);
        config()->set('license_import.validation.comparative.field_fill_rate_variation', [
            'warning_percent' => 101,
            'reject_percent' => 101,
        ]);

        $previousBatch = $this->createActivePreviousBatchWithSummary(
            rawRowsCount: 2,
            validRowsCount: 2,
            invalidRowsCount: 0,
            requiredFieldFillRates: [
                'license_number' => 100,
                'last_name' => 100,
                'first_name' => 100,
            ],
        );

        $this->fakeSuccessfulHtmlResponse(
            $this->telematHtmlWithSingleValidRow()
        );

        $context = $this->makeExecutionContext(dryRun: false);

        /** @var TelematLicenseImportOrchestrator $orchestrator */
        $orchestrator = app(TelematLicenseImportOrchestrator::class);

        $this->expectException(ComparativeValidationFailedException::class);
        $this->expectExceptionMessage('Comparative validation failed for the current import batch.');

        try {
            $orchestrator->run($context);
        } finally {
            /** @var LicenseImportBatch|null $failedBatch */
            $failedBatch = LicenseImportBatch::query()
                ->where('source', 'ffbi_telemat')
                ->latest('id')
                ->first();

            $this->assertNotNull($failedBatch);
            $this->assertNotSame($previousBatch->id, $failedBatch->id);

            $this->assertSame('ffbi_telemat', $failedBatch->source);

            $this->assertBatchFailed(
                $failedBatch,
                expectedFailureStage: 'comparative_validation',
                expectedFailureCode: 'comparative_validation_failed',
                expectedFailureMessage: 'Comparative validation failed for the current import batch.',
            );

            $summary = is_array($failedBatch->summary) ? $failedBatch->summary : [];

            $this->assertSame(1, $failedBatch->raw_rows_count);
            $this->assertSame(1, $failedBatch->valid_rows_count);
            $this->assertSame(0, $failedBatch->invalid_rows_count);
            $this->assertSame(2, $failedBatch->error_count);
            $this->assertSame(0, $failedBatch->warning_count);

            $snapshots = $failedBatch->snapshots()->orderBy('row_index')->get();

            $this->assertCount(1, $snapshots);
            $this->assertTrue((bool) $snapshots[0]->is_valid);

            $issues = $failedBatch->issues()->orderBy('id')->get();

            $this->assertCount(
                2,
                $issues,
                'Le batch doit contenir 2 issues : comparative_total_rows_variation_reject et comparative_validation_failed.'
            );

            $comparativeRejectIssue = $failedBatch->issues()
                ->where('code', 'comparative_total_rows_variation_reject')
                ->first();

            $comparativeValidationFailureIssue = $failedBatch->issues()
                ->where('code', 'comparative_validation_failed')
                ->first();

            $this->assertNotNull($comparativeRejectIssue);
            $this->assertNotNull($comparativeValidationFailureIssue);

            $this->assertSame(IssueSeverity::Error, $comparativeRejectIssue->severity);
            $this->assertNull($comparativeRejectIssue->row_index);

            $comparativeContext = is_array($comparativeRejectIssue->context) ? $comparativeRejectIssue->context : [];
            $this->assertSame('total rows count', $comparativeContext['metric'] ?? null);
            $this->assertSame(1, $comparativeContext['current_value'] ?? null);
            $this->assertSame(2, $comparativeContext['previous_value'] ?? null);
            $this->assertSame(50, $comparativeContext['variation_percent'] ?? null);
            $this->assertSame(25, $comparativeContext['warning_percent'] ?? null);
            $this->assertSame(40, $comparativeContext['reject_percent'] ?? null);

            $this->assertSame(IssueSeverity::Error, $comparativeValidationFailureIssue->severity);
            $this->assertNull($comparativeValidationFailureIssue->row_index);
            $this->assertSame(
                'Comparative validation failed for the current import batch.',
                $comparativeValidationFailureIssue->message
            );

            $comparativeSummary = is_array($summary['comparative_validation'] ?? null)
                ? $summary['comparative_validation']
                : [];

            $this->assertSame(true, $comparativeSummary['enabled'] ?? null);
            $this->assertSame(false, $comparativeSummary['skipped'] ?? null);
            $this->assertSame($previousBatch->id, $comparativeSummary['previous_batch_id'] ?? null);

            $currentSummary = is_array($comparativeSummary['current'] ?? null) ? $comparativeSummary['current'] : [];
            $previousSummary = is_array($comparativeSummary['previous'] ?? null) ? $comparativeSummary['previous'] : [];

            $this->assertSame(1, $currentSummary['raw_rows_count'] ?? null);
            $this->assertSame(1, $currentSummary['valid_rows_count'] ?? null);
            $this->assertSame(0, $currentSummary['invalid_rows_count'] ?? null);
            $this->assertSame(0, $currentSummary['invalid_ratio_percent'] ?? null);

            $this->assertSame(2, $previousSummary['raw_rows_count'] ?? null);
            $this->assertSame(2, $previousSummary['valid_rows_count'] ?? null);
            $this->assertSame(0, $previousSummary['invalid_rows_count'] ?? null);
            $this->assertSame(0, $previousSummary['invalid_ratio_percent'] ?? null);

            $this->assertPreviousBatchRemainsActive($previousBatch);
        }
    }

    public function test_it_fails_when_fetch_raises_a_network_exception(): void
    {
        $previousBatch = $this->createActivePreviousBatch();

        Http::fake([
            '*' => function (): never {
                throw new ConnectionException('Connection failed.');
            },
        ]);

        $context = $this->makeExecutionContext(dryRun: false);

        /** @var TelematLicenseImportOrchestrator $orchestrator */
        $orchestrator = app(TelematLicenseImportOrchestrator::class);

        $this->expectException(TelematNetworkException::class);
        $this->expectExceptionMessage('Telemat fetch network failure for [https://example.test/licenses].');

        try {
            $orchestrator->run($context);
        } finally {
            /** @var LicenseImportBatch|null $failedBatch */
            $failedBatch = LicenseImportBatch::query()
                ->where('source', 'ffbi_telemat')
                ->latest('id')
                ->first();

            $this->assertNotNull($failedBatch, 'Un nouveau batch devrait avoir été créé avant l’échec de fetch.');
            $this->assertNotSame($previousBatch->id, $failedBatch->id, 'Le batch en échec doit être distinct du batch précédent.');

            $this->assertSame('ffbi_telemat', $failedBatch->source);

            $this->assertBatchFailed(
                $failedBatch,
                expectedFailureStage: 'fetch',
                expectedFailureCode: 'fetch_network_failure',
                expectedFailureMessage: 'Telemat fetch network failure for [https://example.test/licenses].',
            );

            $this->assertSame(0, $failedBatch->raw_rows_count);
            $this->assertSame(0, $failedBatch->valid_rows_count);
            $this->assertSame(0, $failedBatch->invalid_rows_count);
            $this->assertSame(1, $failedBatch->error_count);
            $this->assertSame(0, $failedBatch->warning_count);

            $this->assertCount(0, $failedBatch->snapshots()->get());

            $issues = $failedBatch->issues()->get();

            $this->assertCount(1, $issues);
            $this->assertSame(IssueSeverity::Error, $issues[0]->severity);
            $this->assertSame('fetch_network_failure', $issues[0]->code);
            $this->assertSame('Telemat fetch network failure for [https://example.test/licenses].', $issues[0]->message);
            $this->assertNull($issues[0]->row_index);

            $issueContext = is_array($issues[0]->context) ? $issues[0]->context : [];

            $this->assertSame(TelematNetworkException::class, $issueContext['exception_class'] ?? null);
            $this->assertSame('ffbi_telemat', $issueContext['source'] ?? null);
            $this->assertSame('manual', $issueContext['trigger_type'] ?? null);
            $this->assertSame('phpunit-license-import-test', $issueContext['triggered_by'] ?? null);
            $this->assertFalse($issueContext['dry_run'] ?? true);
            $this->assertArrayNotHasKey('reason', $issueContext);
            $this->assertArrayNotHasKey('response_context', $issueContext);

            $this->assertPreviousBatchRemainsActive($previousBatch);
        }
    }

    public function test_it_fails_when_selected_html_table_does_not_contain_exploitable_headers(): void
    {
        $previousBatch = $this->createActivePreviousBatch();

        $this->swapTelematFetcher(new class implements TelematFetcherInterface {
            public function fetch(TelematFetchConfig $config): TelematFetchResult
            {
                return new TelematFetchResult(
                    html: <<<'HTML'
                    <html>
                    <body>
                        <table id="licenses">
                            <tbody>
                                <tr></tr>
                                <tr></tr>
                            </tbody>
                        </table>
                    </body>
                    </html>
                    HTML,
                    httpStatus: 200,
                    contentType: 'text/html; charset=UTF-8',
                    finalUrl: 'https://example.test/licenses',
                    fetchedAt: new DateTimeImmutable(),
                );
            }
        });

        $context = $this->makeExecutionContext(dryRun: false);

        /** @var TelematLicenseImportOrchestrator $orchestrator */
        $orchestrator = app(TelematLicenseImportOrchestrator::class);

        $this->expectException(TelematHtmlParseException::class);
        $this->expectExceptionMessage('The selected HTML table does not contain exploitable headers.');

        try {
            $orchestrator->run($context);
        } finally {
            /** @var LicenseImportBatch|null $failedBatch */
            $failedBatch = LicenseImportBatch::query()
                ->where('source', 'ffbi_telemat')
                ->latest('id')
                ->first();

            $this->assertNotNull($failedBatch);
            $this->assertNotSame($previousBatch->id, $failedBatch->id);

            $this->assertSame('ffbi_telemat', $failedBatch->source);

            $this->assertBatchFailed(
                $failedBatch,
                expectedFailureStage: 'parse',
                expectedFailureCode: 'parse_failed',
                expectedFailureMessage: 'The selected HTML table does not contain exploitable headers.',
            );

            $this->assertSame(0, $failedBatch->raw_rows_count);
            $this->assertSame(0, $failedBatch->valid_rows_count);
            $this->assertSame(0, $failedBatch->invalid_rows_count);
            $this->assertSame(1, $failedBatch->error_count);
            $this->assertSame(0, $failedBatch->warning_count);

            $this->assertCount(0, $failedBatch->snapshots()->get());

            $issues = $failedBatch->issues()->get();

            $this->assertCount(1, $issues);
            $this->assertSame(IssueSeverity::Error, $issues[0]->severity);
            $this->assertSame('parse_failed', $issues[0]->code);
            $this->assertSame(
                'The selected HTML table does not contain exploitable headers.',
                $issues[0]->message
            );
            $this->assertNull($issues[0]->row_index);

            $this->assertPreviousBatchRemainsActive($previousBatch);
        }
    }

    public function test_it_fails_when_selected_html_table_contains_fewer_rows_than_expected(): void
    {
        config()->set('license_import.parsing.minimum_detected_rows', 2);

        $previousBatch = $this->createActivePreviousBatch();

        $this->swapTelematFetcher(new class implements TelematFetcherInterface {
            public function fetch(TelematFetchConfig $config): TelematFetchResult
            {
                return new TelematFetchResult(
                    html: <<<'HTML'
                    <html>
                    <body>
                        <table id="licenses">
                        <thead>
                            <tr>
                            <th>Num&eacute;ro</th>
                            <th>Nom</th>
                            <th>Pr&eacute;nom</th>
                            <th>Cat&eacute;gorie</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                            <td><a href="./?cs=token-1">191100 S</a></td>
                            <td><a href="./?cs=token-1">ARROUAS</a></td>
                            <td><a href="./?cs=token-1">ISABELLE</a></td>
                            <td class="c"><a href="./?cs=token-1">Decouverte</a></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            </tr>
                        </tbody>
                        </table>
                    </body>
                    </html>
                    HTML,
                    httpStatus: 200,
                    contentType: 'text/html; charset=UTF-8',
                    finalUrl: 'https://example.test/licenses',
                    fetchedAt: new DateTimeImmutable(),
                );
            }
        });

        $context = $this->makeExecutionContext(dryRun: false);

        /** @var TelematLicenseImportOrchestrator $orchestrator */
        $orchestrator = app(TelematLicenseImportOrchestrator::class);

        $this->expectException(TelematHtmlParseException::class);
        $this->expectExceptionMessage('The selected HTML table contains fewer rows than expected [1 < 2].');

        try {
            $orchestrator->run($context);
        } finally {
            /** @var LicenseImportBatch|null $failedBatch */
            $failedBatch = LicenseImportBatch::query()
                ->where('source', 'ffbi_telemat')
                ->latest('id')
                ->first();

            $this->assertNotNull($failedBatch);
            $this->assertNotSame($previousBatch->id, $failedBatch->id);

            $this->assertSame('ffbi_telemat', $failedBatch->source);

            $this->assertBatchFailed(
                $failedBatch,
                expectedFailureStage: 'parse',
                expectedFailureCode: 'parse_failed',
                expectedFailureMessage: 'The selected HTML table contains fewer rows than expected [1 < 2].',
            );

            $this->assertSame(0, $failedBatch->raw_rows_count);
            $this->assertSame(0, $failedBatch->valid_rows_count);
            $this->assertSame(0, $failedBatch->invalid_rows_count);
            $this->assertSame(1, $failedBatch->error_count);
            $this->assertSame(0, $failedBatch->warning_count);

            $this->assertCount(0, $failedBatch->snapshots()->get());

            $issues = $failedBatch->issues()->get();

            $this->assertCount(1, $issues);
            $this->assertSame(IssueSeverity::Error, $issues[0]->severity);
            $this->assertSame('parse_failed', $issues[0]->code);
            $this->assertSame(
                'The selected HTML table contains fewer rows than expected [1 < 2].',
                $issues[0]->message
            );
            $this->assertNull($issues[0]->row_index);

            $this->assertPreviousBatchRemainsActive($previousBatch);
        }
    }

    public function test_it_fails_when_fetch_raises_a_generic_telemat_fetch_exception(): void
    {
        $previousBatch = $this->createActivePreviousBatch();

        $this->swapTelematFetcher(
            new \Tests\Integration\Domain\LicenseImport\Fakes\FailingTelematFetcher(
                new \App\Domain\LicenseImport\Fetch\Exceptions\TelematFetchException(
                    'Unexpected Telemat fetch failure.'
                )
            )
        );

        $context = $this->makeExecutionContext(dryRun: false);

        /** @var TelematLicenseImportOrchestrator $orchestrator */
        $orchestrator = app(TelematLicenseImportOrchestrator::class);

        $this->expectException(\App\Domain\LicenseImport\Fetch\Exceptions\TelematFetchException::class);
        $this->expectExceptionMessage('Unexpected Telemat fetch failure.');

        try {
            $orchestrator->run($context);
        } finally {
            /** @var LicenseImportBatch|null $failedBatch */
            $failedBatch = LicenseImportBatch::query()
                ->where('source', 'ffbi_telemat')
                ->latest('id')
                ->first();

            $this->assertNotNull($failedBatch, 'Un nouveau batch devrait avoir été créé avant l’échec de fetch.');
            $this->assertNotSame($previousBatch->id, $failedBatch->id, 'Le batch en échec doit être distinct du batch précédent.');

            $this->assertSame('ffbi_telemat', $failedBatch->source);

            $this->assertBatchFailed(
                $failedBatch,
                expectedFailureStage: 'fetch',
                expectedFailureCode: 'fetch_failed',
                expectedFailureMessage: 'Unexpected Telemat fetch failure.',
            );

            $this->assertSame(0, $failedBatch->raw_rows_count);
            $this->assertSame(0, $failedBatch->valid_rows_count);
            $this->assertSame(0, $failedBatch->invalid_rows_count);
            $this->assertSame(1, $failedBatch->error_count);
            $this->assertSame(0, $failedBatch->warning_count);

            $this->assertCount(0, $failedBatch->snapshots()->get());

            $issues = $failedBatch->issues()->get();

            $this->assertCount(1, $issues);
            $this->assertSame(IssueSeverity::Error, $issues[0]->severity);
            $this->assertSame('fetch_failed', $issues[0]->code);
            $this->assertSame('Unexpected Telemat fetch failure.', $issues[0]->message);
            $this->assertNull($issues[0]->row_index);

            $issueContext = is_array($issues[0]->context) ? $issues[0]->context : [];

            $this->assertSame(\App\Domain\LicenseImport\Fetch\Exceptions\TelematFetchException::class, $issueContext['exception_class'] ?? null);
            $this->assertSame('ffbi_telemat', $issueContext['source'] ?? null);
            $this->assertSame('manual', $issueContext['trigger_type'] ?? null);
            $this->assertSame('phpunit-license-import-test', $issueContext['triggered_by'] ?? null);
            $this->assertFalse($issueContext['dry_run'] ?? true);

            $this->assertPreviousBatchRemainsActive($previousBatch);
        }
    }

    public function test_it_fails_when_required_field_fill_rate_drops_below_configured_threshold(): void
    {
        config()->set('license_import.validation.minimal.minimum_raw_rows_count', 1);
        config()->set('license_import.validation.minimal.minimum_valid_ratio_percent', 0);
        config()->set('license_import.validation.minimal.maximum_invalid_ratio_percent', 100);
        config()->set('license_import.validation.minimal.required_field_fill_rate_percent', [
            'first_name' => 100,
        ]);

        $previousBatch = $this->createActivePreviousBatch();

        $this->fakeSuccessfulHtmlResponse(
            $this->telematHtmlWithMissingRequiredFirstNameOnSecondRow()
        );

        $context = $this->makeExecutionContext(dryRun: false);

        /** @var TelematLicenseImportOrchestrator $orchestrator */
        $orchestrator = app(TelematLicenseImportOrchestrator::class);

        $this->expectException(MinimalValidationFailedException::class);
        $this->expectExceptionMessage('Minimal validation failed for the current import batch.');

        try {
            $orchestrator->run($context);
        } finally {
            /** @var LicenseImportBatch|null $failedBatch */
            $failedBatch = LicenseImportBatch::query()
                ->where('source', 'ffbi_telemat')
                ->latest('id')
                ->first();

            $this->assertNotNull($failedBatch);
            $this->assertNotSame($previousBatch->id, $failedBatch->id);

            $this->assertSame('ffbi_telemat', $failedBatch->source);

            $this->assertBatchFailed(
                $failedBatch,
                expectedFailureStage: 'minimal_validation',
                expectedFailureCode: 'minimal_validation_failed',
                expectedFailureMessage: 'Minimal validation failed for the current import batch.',
            );

            $summary = is_array($failedBatch->summary) ? $failedBatch->summary : [];

            $this->assertSame(2, $failedBatch->raw_rows_count);
            $this->assertSame(1, $failedBatch->valid_rows_count);
            $this->assertSame(1, $failedBatch->invalid_rows_count);
            $this->assertSame(1, $failedBatch->warning_count);
            $this->assertSame(2, $failedBatch->error_count);

            $snapshots = $failedBatch->snapshots()->orderBy('row_index')->get();

            $this->assertCount(2, $snapshots);
            $this->assertTrue((bool) $snapshots[0]->is_valid);
            $this->assertFalse((bool) $snapshots[1]->is_valid);

            $validationFlags = is_array($snapshots[1]->validation_flags) ? $snapshots[1]->validation_flags : [];
            $this->assertContains(
                'missing_required_field:first_name',
                $validationFlags
            );

            $issues = $failedBatch->issues()->orderBy('id')->get();

            $this->assertCount(
                3,
                $issues,
                'Le batch doit contenir 3 issues : missing_required_field, required_field_fill_rate_below_threshold et minimal_validation_failed.'
            );

            $missingRequiredFieldIssue = $failedBatch->issues()->where('code', 'missing_required_field')->first();
            $fillRateIssue = $failedBatch->issues()->where('code', 'required_field_fill_rate_below_threshold')->first();
            $minimalValidationFailureIssue = $failedBatch->issues()->where('code', 'minimal_validation_failed')->first();

            $this->assertNotNull($missingRequiredFieldIssue);
            $this->assertNotNull($fillRateIssue);
            $this->assertNotNull($minimalValidationFailureIssue);

            $this->assertNull(
                $failedBatch->issues()->where('code', 'minimum_valid_ratio_not_reached')->first()
            );

            $this->assertNull(
                $failedBatch->issues()->where('code', 'maximum_invalid_ratio_exceeded')->first()
            );

            $this->assertSame(IssueSeverity::Warning, $missingRequiredFieldIssue->severity);
            $this->assertSame(1, $missingRequiredFieldIssue->row_index);
            $this->assertStringContainsString('first_name', $missingRequiredFieldIssue->message);

            $this->assertSame(IssueSeverity::Error, $fillRateIssue->severity);
            $this->assertNull($fillRateIssue->row_index);

            $fillRateContext = is_array($fillRateIssue->context) ? $fillRateIssue->context : [];
            $this->assertSame('first_name', $fillRateContext['field'] ?? null);
            $this->assertSame(1, $fillRateContext['filled_rows_count'] ?? null);
            $this->assertSame(2, $fillRateContext['raw_rows_count'] ?? null);
            $this->assertSame(50, $fillRateContext['actual_fill_rate_percent'] ?? null);
            $this->assertSame(100, $fillRateContext['minimum_fill_rate_percent'] ?? null);

            $this->assertSame(IssueSeverity::Error, $minimalValidationFailureIssue->severity);
            $this->assertNull($minimalValidationFailureIssue->row_index);
            $this->assertSame(
                'Minimal validation failed for the current import batch.',
                $minimalValidationFailureIssue->message
            );

            $minimalValidationSummary = is_array($summary['minimal_validation'] ?? null)
                ? $summary['minimal_validation']
                : [];

            $this->assertSame(2, $minimalValidationSummary['raw_rows_count'] ?? null);
            $this->assertSame(1, $minimalValidationSummary['valid_rows_count'] ?? null);
            $this->assertSame(1, $minimalValidationSummary['invalid_rows_count'] ?? null);
            $this->assertSame(50, $minimalValidationSummary['valid_ratio_percent'] ?? null);
            $this->assertSame(50, $minimalValidationSummary['invalid_ratio_percent'] ?? null);

            $requiredFieldFillRates = is_array($minimalValidationSummary['required_field_fill_rates'] ?? null)
                ? $minimalValidationSummary['required_field_fill_rates']
                : [];

            $this->assertSame(50, $requiredFieldFillRates['first_name'] ?? null);

            $this->assertPreviousBatchRemainsActive($previousBatch);
        }
    }

    public function test_it_fails_when_invalid_ratio_exceeds_configured_maximum_threshold(): void
    {
        config()->set('license_import.validation.minimal.minimum_raw_rows_count', 1);
        config()->set('license_import.validation.minimal.minimum_valid_ratio_percent', 0);
        config()->set('license_import.validation.minimal.maximum_invalid_ratio_percent', 0);
        config()->set('license_import.validation.minimal.required_field_fill_rate_percent', []);

        $previousBatch = $this->createActivePreviousBatch();

        $this->fakeSuccessfulHtmlResponse(
            $this->telematHtmlWithMissingRequiredFirstNameOnSecondRow()
        );

        $context = $this->makeExecutionContext(dryRun: false);

        /** @var TelematLicenseImportOrchestrator $orchestrator */
        $orchestrator = app(TelematLicenseImportOrchestrator::class);

        $this->expectException(MinimalValidationFailedException::class);
        $this->expectExceptionMessage('Minimal validation failed for the current import batch.');

        try {
            $orchestrator->run($context);
        } finally {
            /** @var LicenseImportBatch|null $failedBatch */
            $failedBatch = LicenseImportBatch::query()
                ->where('source', 'ffbi_telemat')
                ->latest('id')
                ->first();

            $this->assertNotNull($failedBatch);
            $this->assertNotSame($previousBatch->id, $failedBatch->id);

            $this->assertSame('ffbi_telemat', $failedBatch->source);

            $this->assertBatchFailed(
                $failedBatch,
                expectedFailureStage: 'minimal_validation',
                expectedFailureCode: 'minimal_validation_failed',
                expectedFailureMessage: 'Minimal validation failed for the current import batch.',
            );

            $summary = is_array($failedBatch->summary) ? $failedBatch->summary : [];

            $this->assertSame(2, $failedBatch->raw_rows_count);
            $this->assertSame(1, $failedBatch->valid_rows_count);
            $this->assertSame(1, $failedBatch->invalid_rows_count);
            $this->assertSame(2, $failedBatch->error_count);
            $this->assertSame(1, $failedBatch->warning_count);

            $snapshots = $failedBatch->snapshots()->orderBy('row_index')->get();

            $this->assertCount(2, $snapshots);
            $this->assertTrue((bool) $snapshots[0]->is_valid);
            $this->assertFalse((bool) $snapshots[1]->is_valid);

            $validationFlags = is_array($snapshots[1]->validation_flags) ? $snapshots[1]->validation_flags : [];
            $this->assertContains(
                'missing_required_field:first_name',
                $validationFlags
            );

            $issues = $failedBatch->issues()->orderBy('id')->get();

            $this->assertCount(
                3,
                $issues,
                'Le batch doit contenir 3 issues : missing_required_field, maximum_invalid_ratio_exceeded et minimal_validation_failed.'
            );

            $missingRequiredFieldIssue = $failedBatch->issues()->where('code', 'missing_required_field')->first();
            $maximumInvalidRatioIssue = $failedBatch->issues()->where('code', 'maximum_invalid_ratio_exceeded')->first();
            $minimalValidationFailureIssue = $failedBatch->issues()->where('code', 'minimal_validation_failed')->first();

            $this->assertNotNull($missingRequiredFieldIssue);
            $this->assertNotNull($maximumInvalidRatioIssue);
            $this->assertNotNull($minimalValidationFailureIssue);

            $this->assertNull(
                $failedBatch->issues()->where('code', 'minimum_valid_ratio_not_reached')->first()
            );

            $this->assertNull(
                $failedBatch->issues()->where('code', 'required_field_fill_rate_below_threshold')->first()
            );

            $this->assertSame(IssueSeverity::Warning, $missingRequiredFieldIssue->severity);
            $this->assertSame(1, $missingRequiredFieldIssue->row_index);
            $this->assertStringContainsString('first_name', $missingRequiredFieldIssue->message);

            $this->assertSame(IssueSeverity::Error, $maximumInvalidRatioIssue->severity);
            $this->assertNull($maximumInvalidRatioIssue->row_index);

            $maximumInvalidRatioContext = is_array($maximumInvalidRatioIssue->context) ? $maximumInvalidRatioIssue->context : [];
            $this->assertSame(1, $maximumInvalidRatioContext['invalid_rows_count'] ?? null);
            $this->assertSame(2, $maximumInvalidRatioContext['raw_rows_count'] ?? null);
            $this->assertSame(50, $maximumInvalidRatioContext['invalid_ratio_percent'] ?? null);
            $this->assertSame(0, $maximumInvalidRatioContext['maximum_invalid_ratio_percent'] ?? null);

            $this->assertSame(IssueSeverity::Error, $minimalValidationFailureIssue->severity);
            $this->assertNull($minimalValidationFailureIssue->row_index);
            $this->assertSame(
                'Minimal validation failed for the current import batch.',
                $minimalValidationFailureIssue->message
            );

            $minimalValidationSummary = is_array($summary['minimal_validation'] ?? null)
                ? $summary['minimal_validation']
                : [];

            $this->assertSame(2, $minimalValidationSummary['raw_rows_count'] ?? null);
            $this->assertSame(1, $minimalValidationSummary['valid_rows_count'] ?? null);
            $this->assertSame(1, $minimalValidationSummary['invalid_rows_count'] ?? null);
            $this->assertSame(50, $minimalValidationSummary['valid_ratio_percent'] ?? null);
            $this->assertSame(50, $minimalValidationSummary['invalid_ratio_percent'] ?? null);

            $this->assertPreviousBatchRemainsActive($previousBatch);
        }
    }

    public function test_it_fails_when_comparative_validation_detects_required_field_fill_rate_variation_above_reject_threshold(): void
    {
        config()->set('license_import.validation.minimal.minimum_raw_rows_count', 1);
        config()->set('license_import.validation.minimal.minimum_valid_ratio_percent', 0);
        config()->set('license_import.validation.minimal.maximum_invalid_ratio_percent', 100);
        config()->set('license_import.validation.minimal.required_field_fill_rate_percent', []);
        config()->set('license_import.validation.comparative.enabled', true);

        config()->set('license_import.validation.comparative.total_rows_variation', [
            'warning_percent' => 101,
            'reject_percent' => 101,
        ]);
        config()->set('license_import.validation.comparative.valid_rows_variation', [
            'warning_percent' => 101,
            'reject_percent' => 101,
        ]);
        config()->set('license_import.validation.comparative.invalid_ratio_variation', [
            'warning_percent' => 101,
            'reject_percent' => 101,
        ]);
        config()->set('license_import.validation.comparative.field_fill_rate_variation', [
            'warning_percent' => 25,
            'reject_percent' => 40,
        ]);

        $previousBatch = $this->createActivePreviousBatchWithSummary(
            rawRowsCount: 2,
            validRowsCount: 2,
            invalidRowsCount: 0,
            requiredFieldFillRates: [
                'license_number' => 100,
                'last_name' => 100,
                'first_name' => 100,
            ],
        );

        $this->fakeSuccessfulHtmlResponse(
            $this->telematHtmlWithMissingRequiredFirstNameOnSecondRow()
        );

        $context = $this->makeExecutionContext(dryRun: false);

        /** @var TelematLicenseImportOrchestrator $orchestrator */
        $orchestrator = app(TelematLicenseImportOrchestrator::class);

        $this->expectException(ComparativeValidationFailedException::class);
        $this->expectExceptionMessage('Comparative validation failed for the current import batch.');

        try {
            $orchestrator->run($context);
        } finally {
            /** @var LicenseImportBatch|null $failedBatch */
            $failedBatch = LicenseImportBatch::query()
                ->where('source', 'ffbi_telemat')
                ->latest('id')
                ->first();

            $this->assertNotNull($failedBatch);
            $this->assertNotSame($previousBatch->id, $failedBatch->id);

            $this->assertSame('ffbi_telemat', $failedBatch->source);

            $this->assertBatchFailed(
                $failedBatch,
                expectedFailureStage: 'comparative_validation',
                expectedFailureCode: 'comparative_validation_failed',
                expectedFailureMessage: 'Comparative validation failed for the current import batch.',
            );

            $summary = is_array($failedBatch->summary) ? $failedBatch->summary : [];

            $this->assertSame(2, $failedBatch->raw_rows_count);
            $this->assertSame(1, $failedBatch->valid_rows_count);
            $this->assertSame(1, $failedBatch->invalid_rows_count);
            $this->assertSame(2, $failedBatch->error_count);
            $this->assertSame(1, $failedBatch->warning_count);

            $snapshots = $failedBatch->snapshots()->orderBy('row_index')->get();

            $this->assertCount(2, $snapshots);
            $this->assertTrue((bool) $snapshots[0]->is_valid);
            $this->assertFalse((bool) $snapshots[1]->is_valid);

            $issues = $failedBatch->issues()->orderBy('id')->get();

            $this->assertCount(
                3,
                $issues,
                'Le batch doit contenir 3 issues : missing_required_field, comparative_field_fill_rate_variation_first_name_reject et comparative_validation_failed.'
            );

            $missingRequiredFieldIssue = $failedBatch->issues()
                ->where('code', 'missing_required_field')
                ->first();

            $comparativeRejectIssue = $failedBatch->issues()
                ->where('code', 'comparative_field_fill_rate_variation_first_name_reject')
                ->first();

            $comparativeValidationFailureIssue = $failedBatch->issues()
                ->where('code', 'comparative_validation_failed')
                ->first();

            $this->assertNotNull($missingRequiredFieldIssue);
            $this->assertNotNull($comparativeRejectIssue);
            $this->assertNotNull($comparativeValidationFailureIssue);

            $this->assertSame(IssueSeverity::Warning, $missingRequiredFieldIssue->severity);
            $this->assertSame(1, $missingRequiredFieldIssue->row_index);

            $this->assertSame(IssueSeverity::Error, $comparativeRejectIssue->severity);
            $this->assertNull($comparativeRejectIssue->row_index);

            $comparativeContext = is_array($comparativeRejectIssue->context) ? $comparativeRejectIssue->context : [];
            $this->assertSame('field fill rate "first_name"', $comparativeContext['metric'] ?? null);
            $this->assertSame(50, $comparativeContext['current_value'] ?? null);
            $this->assertSame(100, $comparativeContext['previous_value'] ?? null);
            $this->assertSame(50, $comparativeContext['variation_percent'] ?? null);
            $this->assertSame(25, $comparativeContext['warning_percent'] ?? null);
            $this->assertSame(40, $comparativeContext['reject_percent'] ?? null);

            $this->assertSame(IssueSeverity::Error, $comparativeValidationFailureIssue->severity);
            $this->assertNull($comparativeValidationFailureIssue->row_index);
            $this->assertSame(
                'Comparative validation failed for the current import batch.',
                $comparativeValidationFailureIssue->message
            );

            $minimalValidationSummary = is_array($summary['minimal_validation'] ?? null)
                ? $summary['minimal_validation']
                : [];
            $comparativeSummary = is_array($summary['comparative_validation'] ?? null)
                ? $summary['comparative_validation']
                : [];

            $requiredFieldFillRates = is_array($minimalValidationSummary['required_field_fill_rates'] ?? null)
                ? $minimalValidationSummary['required_field_fill_rates']
                : [];

            $this->assertSame(50, $requiredFieldFillRates['first_name'] ?? null);

            $this->assertTrue($comparativeSummary['enabled'] ?? false);
            $this->assertFalse($comparativeSummary['skipped'] ?? true);
            $this->assertSame($previousBatch->id, $comparativeSummary['previous_batch_id'] ?? null);

            $currentSummary = is_array($comparativeSummary['current'] ?? null) ? $comparativeSummary['current'] : [];
            $previousSummary = is_array($comparativeSummary['previous'] ?? null) ? $comparativeSummary['previous'] : [];

            $currentFillRates = is_array($currentSummary['required_field_fill_rates'] ?? null)
                ? $currentSummary['required_field_fill_rates']
                : [];
            $previousFillRates = is_array($previousSummary['required_field_fill_rates'] ?? null)
                ? $previousSummary['required_field_fill_rates']
                : [];

            $this->assertSame(50, $currentFillRates['first_name'] ?? null);
            $this->assertSame(100, $previousFillRates['first_name'] ?? null);

            $this->assertPreviousBatchRemainsActive($previousBatch);
        }
    }

    public function test_it_fails_when_required_fields_cannot_be_mapped_from_source_headers(): void
    {
        $previousBatch = $this->createActivePreviousBatch();

        $this->fakeSuccessfulHtmlResponse(
            $this->telematHtmlWithHeadersThatDoNotMatchRequiredFieldMapping()
        );

        $context = $this->makeExecutionContext(dryRun: false);

        /** @var TelematLicenseImportOrchestrator $orchestrator */
        $orchestrator = app(TelematLicenseImportOrchestrator::class);

        $this->expectException(MinimalValidationFailedException::class);
        $this->expectExceptionMessage('Minimal validation failed for the current import batch.');

        try {
            $orchestrator->run($context);
        } finally {
            /** @var LicenseImportBatch|null $failedBatch */
            $failedBatch = LicenseImportBatch::query()
                ->where('source', 'ffbi_telemat')
                ->latest('id')
                ->first();

            $this->assertNotNull($failedBatch);
            $this->assertNotSame($previousBatch->id, $failedBatch->id);

            $this->assertSame('ffbi_telemat', $failedBatch->source);

            $this->assertBatchFailed(
                $failedBatch,
                expectedFailureStage: 'minimal_validation',
                expectedFailureCode: 'minimal_validation_failed',
                expectedFailureMessage: 'Minimal validation failed for the current import batch.',
            );

            $summary = is_array($failedBatch->summary) ? $failedBatch->summary : [];

            $this->assertSame(2, $failedBatch->raw_rows_count);
            $this->assertSame(0, $failedBatch->valid_rows_count);
            $this->assertSame(2, $failedBatch->invalid_rows_count);
            $this->assertSame(6, $failedBatch->error_count);
            $this->assertSame(6, $failedBatch->warning_count);

            $snapshots = $failedBatch->snapshots()->orderBy('row_index')->get();

            $this->assertCount(2, $snapshots);

            $this->assertFalse((bool) $snapshots[0]->is_valid);
            $this->assertFalse((bool) $snapshots[1]->is_valid);

            $this->assertNull($snapshots[0]->license_number);
            $this->assertNull($snapshots[0]->last_name);
            $this->assertNull($snapshots[0]->first_name);
            $this->assertNull($snapshots[0]->category);

            $this->assertNull($snapshots[1]->license_number);
            $this->assertNull($snapshots[1]->last_name);
            $this->assertNull($snapshots[1]->first_name);
            $this->assertNull($snapshots[1]->category);

            $flagsRow0 = is_array($snapshots[0]->validation_flags) ? $snapshots[0]->validation_flags : [];
            $flagsRow1 = is_array($snapshots[1]->validation_flags) ? $snapshots[1]->validation_flags : [];

            $this->assertContains('missing_required_field:license_number', $flagsRow0);
            $this->assertContains('missing_required_field:last_name', $flagsRow0);
            $this->assertContains('missing_required_field:first_name', $flagsRow0);

            $this->assertContains('missing_required_field:license_number', $flagsRow1);
            $this->assertContains('missing_required_field:last_name', $flagsRow1);
            $this->assertContains('missing_required_field:first_name', $flagsRow1);

            $issues = $failedBatch->issues()->orderBy('id')->get();

            $this->assertCount(
                12,
                $issues,
                'Le batch doit contenir 6 issues missing_required_field, minimum_valid_ratio_not_reached, maximum_invalid_ratio_exceeded, 3 issues required_field_fill_rate_below_threshold et minimal_validation_failed.'
            );

            $this->assertCount(
                6,
                $failedBatch->issues()->where('code', 'missing_required_field')->get()
            );

            $minimumValidRatioIssue = $failedBatch->issues()->where('code', 'minimum_valid_ratio_not_reached')->first();
            $maximumInvalidRatioIssue = $failedBatch->issues()->where('code', 'maximum_invalid_ratio_exceeded')->first();
            $fillRateIssues = $failedBatch->issues()
                ->where('code', 'required_field_fill_rate_below_threshold')
                ->orderBy('id')
                ->get();
            $minimalValidationFailureIssue = $failedBatch->issues()->where('code', 'minimal_validation_failed')->first();

            $this->assertNotNull($minimumValidRatioIssue);
            $this->assertNotNull($maximumInvalidRatioIssue);
            $this->assertCount(3, $fillRateIssues);
            $this->assertNotNull($minimalValidationFailureIssue);

            $this->assertSame(IssueSeverity::Error, $minimumValidRatioIssue->severity);
            $this->assertSame(IssueSeverity::Error, $maximumInvalidRatioIssue->severity);
            $this->assertSame(IssueSeverity::Error, $minimalValidationFailureIssue->severity);

            $fillRateFields = $fillRateIssues
                ->map(function ($issue) {
                    $context = is_array($issue->context) ? $issue->context : [];
                    return $context['field'] ?? null;
                })
                ->all();

            $this->assertEqualsCanonicalizing(
                ['license_number', 'last_name', 'first_name'],
                $fillRateFields
            );

            $minimumValidRatioContext = is_array($minimumValidRatioIssue->context) ? $minimumValidRatioIssue->context : [];
            $this->assertSame(0, $minimumValidRatioContext['valid_rows_count'] ?? null);
            $this->assertSame(2, $minimumValidRatioContext['raw_rows_count'] ?? null);
            $this->assertSame(0, $minimumValidRatioContext['valid_ratio_percent'] ?? null);
            $this->assertSame(100, $minimumValidRatioContext['minimum_valid_ratio_percent'] ?? null);

            $maximumInvalidRatioContext = is_array($maximumInvalidRatioIssue->context) ? $maximumInvalidRatioIssue->context : [];
            $this->assertSame(2, $maximumInvalidRatioContext['invalid_rows_count'] ?? null);
            $this->assertSame(2, $maximumInvalidRatioContext['raw_rows_count'] ?? null);
            $this->assertSame(100, $maximumInvalidRatioContext['invalid_ratio_percent'] ?? null);
            $this->assertSame(0, $maximumInvalidRatioContext['maximum_invalid_ratio_percent'] ?? null);

            $this->assertSame(
                'Minimal validation failed for the current import batch.',
                $minimalValidationFailureIssue->message
            );

            $minimalValidationSummary = is_array($summary['minimal_validation'] ?? null)
                ? $summary['minimal_validation']
                : [];

            $this->assertSame(2, $minimalValidationSummary['raw_rows_count'] ?? null);
            $this->assertSame(0, $minimalValidationSummary['valid_rows_count'] ?? null);
            $this->assertSame(2, $minimalValidationSummary['invalid_rows_count'] ?? null);
            $this->assertSame(0, $minimalValidationSummary['valid_ratio_percent'] ?? null);
            $this->assertSame(100, $minimalValidationSummary['invalid_ratio_percent'] ?? null);

            $requiredFieldFillRates = is_array($minimalValidationSummary['required_field_fill_rates'] ?? null)
                ? $minimalValidationSummary['required_field_fill_rates']
                : [];

            $this->assertSame(0, $requiredFieldFillRates['license_number'] ?? null);
            $this->assertSame(0, $requiredFieldFillRates['last_name'] ?? null);
            $this->assertSame(0, $requiredFieldFillRates['first_name'] ?? null);

            $this->assertSame(
                ['Licence', 'Nom complet', 'Prénom complet', 'Classe'],
                $failedBatch->source_columns
            );

            $this->assertPreviousBatchRemainsActive($previousBatch);
        }
    }

    private function assertLatestFailedBatchCreatedFromPrevious(
        LicenseImportBatch $previousBatch,
        string $expectedFailureStage,
        string $expectedFailureCode,
        string $expectedFailureMessage,
        string $notNullMessage = 'Un nouveau batch devrait avoir été créé.',
        string $notSameMessage = 'Le batch courant doit être distinct du batch précédent.',
    ): LicenseImportBatch {
        /** @var LicenseImportBatch|null $failedBatch */
        $failedBatch = LicenseImportBatch::query()
            ->where('source', 'ffbi_telemat')
            ->latest('id')
            ->first();

        $this->assertNotNull($failedBatch, $notNullMessage);
        $this->assertNotSame($previousBatch->id, $failedBatch->id, $notSameMessage);
        $this->assertSame('ffbi_telemat', $failedBatch->source);

        $this->assertBatchFailed(
            $failedBatch,
            expectedFailureStage: $expectedFailureStage,
            expectedFailureCode: $expectedFailureCode,
            expectedFailureMessage: $expectedFailureMessage,
        );

        $this->assertPreviousBatchRemainsActive($previousBatch);

        return $failedBatch;
    }

    private function telematHtmlWithHeadersThatDoNotMatchRequiredFieldMapping(): string
    {
        return <<<'HTML'
    <html>
    <body>
        <table id="licenses">
        <thead>
            <tr>
            <th>Licence</th>
            <th>Nom complet</th>
            <th>Pr&eacute;nom complet</th>
            <th>Classe</th>
            </tr>
        </thead>
        <tbody>
            <tr>
            <td>191100 S</td>
            <td>ARROUAS</td>
            <td>ISABELLE</td>
            <td>Decouverte</td>
            </tr>
            <tr>
            <td>190399 F</td>
            <td>AUCHART</td>
            <td>THIERRY</td>
            <td>Decouverte</td>
            </tr>
        </tbody>
        </table>
    </body>
    </html>
    HTML;
    }

    private function htmlWithSelectedTableButNoExploitableHeaders(): string
    {
        return <<<'HTML'
    <html>
    <body>
        <table id="licenses">
            <tbody>
                <tr></tr>
                <tr></tr>
            </tbody>
        </table>
    </body>
    </html>
    HTML;
    }

    private function telematHtmlWithSingleValidRow(): string
    {
        return <<<'HTML'
    <html>
    <body>
        <table id="licenses">
        <thead>
            <tr>
            <th>Num&eacute;ro</th>
            <th>Nom</th>
            <th>Pr&eacute;nom</th>
            <th>Cat&eacute;gorie</th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
            </tr>
        </thead>
        <tbody>
            <tr>
            <td><a href="./?cs=token-1">191100 S</a></td>
            <td><a href="./?cs=token-1">ARROUAS</a></td>
            <td><a href="./?cs=token-1">ISABELLE</a></td>
            <td class="c"><a href="./?cs=token-1">Decouverte</a></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
        </tbody>
        </table>
    </body>
    </html>
    HTML;
    }

    private function telematHtmlWithDuplicateLicenseNumbers(): string
    {
        return <<<'HTML'
    <html>
    <body>
        <table id="licenses">
        <thead>
            <tr>
            <th>Num&eacute;ro</th>
            <th>Nom</th>
            <th>Pr&eacute;nom</th>
            <th>Cat&eacute;gorie</th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
            </tr>
        </thead>
        <tbody>
            <tr>
            <td><a href="./?cs=token-1">191100 S</a></td>
            <td><a href="./?cs=token-1">ARROUAS</a></td>
            <td><a href="./?cs=token-1">ISABELLE</a></td>
            <td class="c"><a href="./?cs=token-1">Decouverte</a></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td><a href="./?cs=token-2">191100 S</a></td>
            <td><a href="./?cs=token-2">AUCHART</a></td>
            <td><a href="./?cs=token-2">THIERRY</a></td>
            <td class="c"><a href="./?cs=token-2">Decouverte</a></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
        </tbody>
        </table>
    </body>
    </html>
    HTML;
    }

    private function telematHtmlWithMissingRequiredFirstNameOnSecondRow(): string
    {
        return <<<'HTML'
    <html>
    <body>
        <table id="licenses">
        <thead>
            <tr>
            <th>Num&eacute;ro</th>
            <th>Nom</th>
            <th>Pr&eacute;nom</th>
            <th>Cat&eacute;gorie</th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
            </tr>
        </thead>
        <tbody>
            <tr>
            <td><a href="./?cs=token-1">191100 S</a></td>
            <td><a href="./?cs=token-1">ARROUAS</a></td>
            <td><a href="./?cs=token-1">ISABELLE</a></td>
            <td class="c"><a href="./?cs=token-1">Decouverte</a></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td><a href="./?cs=token-2">190399 F</a></td>
            <td><a href="./?cs=token-2">AUCHART</a></td>
            <td>&nbsp;</td>
            <td class="c"><a href="./?cs=token-2">Decouverte</a></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
        </tbody>
        </table>
    </body>
    </html>
    HTML;
    }

    private function htmlWithoutAnyTable(): string
    {
        return <<<'HTML'
    <html>
    <body>
        <div class="content">
            <h1>Licences</h1>
            <p>Aucune table exploitable dans cette réponse.</p>
        </div>
    </body>
    </html>
    HTML;
    }
}