<?php

declare(strict_types=1);

namespace Tests\Integration\Domain\LicenseImport;

use App\Domain\LicenseImport\Fetch\Contracts\TelematFetcherInterface;
use App\Domain\LicenseImport\Fetch\Exceptions\TelematFetchException;
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

final class TelematLicenseImportOrchestratorRefactoredFailureTest extends TelematLicenseImportOrchestratorTestCase
{
    public function test_it_marks_batch_as_failed_when_fetcher_throws_network_exception(): void
    {
        $this->swapTelematFetcher(new class implements TelematFetcherInterface {
            public function fetch(TelematFetchConfig $config): TelematFetchResult
            {
                throw new TelematNetworkException('Network unreachable during Telemat fetch.');
            }
        });

        $context = $this->makeExecutionContext();

        /** @var TelematLicenseImportOrchestrator $orchestrator */
        $orchestrator = app(TelematLicenseImportOrchestrator::class);

        try {
            $orchestrator->run($context);
            $this->fail('Une TelematNetworkException était attendue.');
        } catch (TelematNetworkException $exception) {
            $this->assertSame('Network unreachable during Telemat fetch.', $exception->getMessage());
        }

        $batch = $this->latestBatch();

        $this->assertNotNull($batch);
        $this->assertBatchFailed(
            $batch,
            expectedFailureStage: 'fetch',
            expectedFailureCode: 'fetch_network_failure',
            expectedFailureMessage: 'Network unreachable during Telemat fetch.',
        );

        $this->assertTrue($batch->issues()->where('code', 'fetch_network_failure')->exists());
        $this->assertSame(1, $batch->issues()->count());
        $this->assertIssueCountersAreConsistent($batch);
        $this->assertSame(0, $batch->snapshots()->count());
    }

    public function test_it_marks_batch_as_failed_when_fetcher_throws_invalid_response_exception(): void
    {
        $this->swapTelematFetcher(new class implements TelematFetcherInterface {
            public function fetch(TelematFetchConfig $config): TelematFetchResult
            {
                throw new TelematInvalidResponseException(
                    message: 'Telemat returned an invalid HTML payload.',
                    reason: 'missing_expected_table',
                    context: [
                        'http_status' => 200,
                        'content_type' => 'text/html',
                        'final_url' => 'https://example.test/licenses',
                    ],
                );
            }
        });

        $context = $this->makeExecutionContext();

        /** @var TelematLicenseImportOrchestrator $orchestrator */
        $orchestrator = app(TelematLicenseImportOrchestrator::class);

        try {
            $orchestrator->run($context);
            $this->fail('Une TelematInvalidResponseException était attendue.');
        } catch (TelematInvalidResponseException $exception) {
            $this->assertSame('Telemat returned an invalid HTML payload.', $exception->getMessage());
            $this->assertSame('missing_expected_table', $exception->reason);
        }

        $batch = $this->latestBatch();

        $this->assertNotNull($batch);
        $this->assertBatchFailed(
            $batch,
            expectedFailureStage: 'fetch',
            expectedFailureCode: 'fetch_invalid_response',
            expectedFailureMessage: 'Telemat returned an invalid HTML payload.',
        );

        $issue = $batch->issues()
            ->where('code', 'fetch_invalid_response')
            ->first();

        $this->assertNotNull($issue);

        $issueContext = is_array($issue->context) ? $issue->context : [];

        $this->assertSame(TelematInvalidResponseException::class, $issueContext['exception_class'] ?? null);
        $this->assertSame('ffbi_telemat', $issueContext['source'] ?? null);
        $this->assertSame('manual', $issueContext['trigger_type'] ?? null);
        $this->assertSame('phpunit-license-import-test', $issueContext['triggered_by'] ?? null);
        $this->assertFalse((bool) ($issueContext['dry_run'] ?? true));
        $this->assertSame('missing_expected_table', $issueContext['reason'] ?? null);
        $this->assertSame([
            'http_status' => 200,
            'content_type' => 'text/html',
            'final_url' => 'https://example.test/licenses',
        ], $issueContext['response_context'] ?? null);

        $this->assertIssueCountersAreConsistent($batch);
        $this->assertSame(0, $batch->snapshots()->count());
    }

    public function test_it_marks_batch_as_failed_with_parse_failed_when_no_html_table_can_be_found(): void
    {
        config()->set('license_import.parsing.table_selector', '#licenses');
        config()->set('license_import.parsing.use_header_detection_fallback', false);

        $this->swapTelematFetcher(new class implements TelematFetcherInterface {
            public function fetch(TelematFetchConfig $config): TelematFetchResult
            {
                return new TelematFetchResult(
                    html: <<<HTML
                    <html>
                        <body>
                            <div>No table here</div>
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

        $context = $this->makeExecutionContext();

        /** @var TelematLicenseImportOrchestrator $orchestrator */
        $orchestrator = app(TelematLicenseImportOrchestrator::class);

        try {
            $orchestrator->run($context);
            $this->fail('Une TelematHtmlParseException était attendue.');
        } catch (TelematHtmlParseException $exception) {
            $this->assertSame(
                'No suitable HTML table could be found in the source document.',
                $exception->getMessage(),
            );
        }

        $batch = $this->latestBatch();

        $this->assertNotNull($batch);
        $this->assertBatchFailed(
            $batch,
            expectedFailureStage: 'parse',
            expectedFailureCode: 'parse_failed',
            expectedFailureMessage: 'No suitable HTML table could be found in the source document.',
        );

        $issue = $batch->issues()
            ->where('code', 'parse_failed')
            ->first();

        $this->assertNotNull($issue);

        $issueContext = is_array($issue->context) ? $issue->context : [];

        $this->assertSame(TelematHtmlParseException::class, $issueContext['exception_class'] ?? null);
        $this->assertSame('parse', $issueContext['failure_stage'] ?? null);

        $this->assertIssueCountersAreConsistent($batch);
        $this->assertSame(0, $batch->snapshots()->count());
    }

    public function test_it_marks_batch_as_failed_with_minimal_validation_failed_when_minimal_validation_rejects_batch(): void
    {
        config()->set('license_import.validation.minimal.minimum_valid_ratio_percent', 100);
        config()->set('license_import.validation.minimal.maximum_invalid_ratio_percent', 100);
        config()->set('license_import.validation.minimal.required_field_fill_rate_percent', [
            'license_number' => 100,
            'last_name' => 100,
            'first_name' => 100,
        ]);
        config()->set('license_import.validation.minimal.duplicate_license_number_policy', 'reject');

        $this->fakeSuccessfulHtmlResponse(<<<HTML
        <html>
            <body>
                <table id="licenses">
                    <thead>
                        <tr>
                            <th>Num Ero</th>
                            <th>Nom</th>
                            <th>Pr Enom</th>
                            <th>Cat Egorie</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td></td>
                            <td>Dupont</td>
                            <td>Jean</td>
                            <td>Senior</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Martin</td>
                            <td>Claire</td>
                            <td>Junior</td>
                        </tr>
                    </tbody>
                </table>
            </body>
        </html>
        HTML);

        $context = $this->makeExecutionContext();

        /** @var TelematLicenseImportOrchestrator $orchestrator */
        $orchestrator = app(TelematLicenseImportOrchestrator::class);

        try {
            $orchestrator->run($context);
            $this->fail('Une MinimalValidationFailedException était attendue.');
        } catch (MinimalValidationFailedException $exception) {
            $this->assertSame('Minimal validation failed for the current import batch.', $exception->getMessage());
        }

        $batch = $this->latestBatch();

        $this->assertNotNull($batch);
        $this->assertBatchFailed(
            $batch,
            expectedFailureStage: 'minimal_validation',
            expectedFailureCode: 'minimal_validation_failed',
            expectedFailureMessage: 'Minimal validation failed for the current import batch.',
        );

        $this->assertTrue($batch->issues()->where('code', 'missing_required_field')->exists());
        $this->assertTrue($batch->issues()->where('code', 'minimum_valid_ratio_not_reached')->exists());
        $this->assertTrue($batch->issues()->where('code', 'required_field_fill_rate_below_threshold')->exists());
        $this->assertTrue($batch->issues()->where('code', 'minimal_validation_failed')->exists());

        $this->assertIssueCountersAreConsistent($batch);
    }

    public function test_it_marks_batch_as_failed_with_comparative_validation_failed_when_comparative_validation_rejects_batch(): void
    {
        $this->createActivePreviousBatchWithSummary(
            rawRowsCount: 4,
            validRowsCount: 4,
            invalidRowsCount: 0,
            requiredFieldFillRates: [
                'license_number' => 100,
                'last_name' => 100,
                'first_name' => 100,
            ],
        );

        config()->set('license_import.validation.minimal.minimum_raw_rows_count', 1);
        config()->set('license_import.validation.minimal.minimum_valid_ratio_percent', 0);
        config()->set('license_import.validation.minimal.maximum_invalid_ratio_percent', 100);
        config()->set('license_import.validation.minimal.required_field_fill_rate_percent', [
            'license_number' => 0,
            'last_name' => 0,
            'first_name' => 0,
        ]);
        config()->set('license_import.validation.minimal.duplicate_license_number_policy', 'allowed');

        config()->set('license_import.validation.comparative.enabled', true);
        config()->set('license_import.validation.comparative.total_rows_variation', [
            'warning_percent' => 10,
            'reject_percent' => 50,
        ]);
        config()->set('license_import.validation.comparative.valid_rows_variation', [
            'warning_percent' => 100,
            'reject_percent' => 100,
        ]);
        config()->set('license_import.validation.comparative.invalid_ratio_variation', [
            'warning_percent' => 100,
            'reject_percent' => 100,
        ]);
        config()->set('license_import.validation.comparative.field_fill_rate_variation', [
            'warning_percent' => 100,
            'reject_percent' => 100,
        ]);

        $this->fakeSuccessfulHtmlResponse(<<<HTML
        <html>
            <body>
                <table id="licenses">
                    <thead>
                        <tr>
                            <th>Num Ero</th>
                            <th>Nom</th>
                            <th>Pr Enom</th>
                            <th>Cat Egorie</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>LIC-001</td>
                            <td>Dupont</td>
                            <td>Jean</td>
                            <td>Senior</td>
                        </tr>
                        <tr>
                            <td>LIC-002</td>
                            <td>Martin</td>
                            <td>Claire</td>
                            <td>Junior</td>
                        </tr>
                    </tbody>
                </table>
            </body>
        </html>
        HTML);

        $context = $this->makeExecutionContext();

        /** @var TelematLicenseImportOrchestrator $orchestrator */
        $orchestrator = app(TelematLicenseImportOrchestrator::class);

        try {
            $orchestrator->run($context);
            $this->fail('Une ComparativeValidationFailedException était attendue.');
        } catch (ComparativeValidationFailedException $exception) {
            $this->assertSame('Comparative validation failed for the current import batch.', $exception->getMessage());
        }

        $batch = $this->latestBatch();

        $this->assertNotNull($batch);
        $this->assertBatchFailed(
            $batch,
            expectedFailureStage: 'comparative_validation',
            expectedFailureCode: 'comparative_validation_failed',
            expectedFailureMessage: 'Comparative validation failed for the current import batch.',
        );

        $this->assertTrue($batch->issues()->where('code', 'comparative_total_rows_variation_reject')->exists());
        $this->assertTrue($batch->issues()->where('code', 'comparative_validation_failed')->exists());

        $this->assertIssueCountersAreConsistent($batch);
    }

    public function test_it_keeps_snapshot_extra_data_and_validation_flags_as_arrays_after_reload(): void
    {
        config()->set('license_import.validation.minimal.minimum_raw_rows_count', 1);
        config()->set('license_import.validation.minimal.minimum_valid_ratio_percent', 0);
        config()->set('license_import.validation.minimal.maximum_invalid_ratio_percent', 100);
        config()->set('license_import.validation.minimal.required_field_fill_rate_percent', [
            'license_number' => 0,
            'last_name' => 0,
            'first_name' => 0,
        ]);
        config()->set('license_import.validation.minimal.duplicate_license_number_policy', 'allowed');

        $this->fakeSuccessfulHtmlResponse(<<<HTML
        <html>
            <body>
                <table id="licenses">
                    <thead>
                        <tr>
                            <th>Num Ero</th>
                            <th>Nom</th>
                            <th>Pr Enom</th>
                            <th>Cat Egorie</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>LIC-001</td>
                            <td>Dupont</td>
                            <td>Jean</td>
                            <td>Senior</td>
                        </tr>
                        <tr>
                            <td>LIC-002</td>
                            <td>Martin</td>
                            <td>Claire</td>
                            <td>Junior</td>
                        </tr>
                    </tbody>
                </table>
            </body>
        </html>
        HTML);

        $context = $this->makeExecutionContext();

        /** @var TelematLicenseImportOrchestrator $orchestrator */
        $orchestrator = app(TelematLicenseImportOrchestrator::class);
        $orchestrator->run($context);

        $batch = $this->latestBatch();

        $this->assertNotNull($batch);

        $snapshots = $batch->snapshots()
            ->orderBy('row_index')
            ->get();

        $this->assertCount(2, $snapshots);

        $this->assertIsArray($snapshots[0]->extra_data);
        $this->assertIsArray($snapshots[0]->validation_flags);

        $this->assertIsArray($snapshots[1]->extra_data);
        $this->assertIsArray($snapshots[1]->validation_flags);

        $firstExtraData = is_array($snapshots[0]->extra_data) ? $snapshots[0]->extra_data : [];
        $secondExtraData = is_array($snapshots[1]->extra_data) ? $snapshots[1]->extra_data : [];

        $this->assertIsArray($firstExtraData['source_row'] ?? null);
        $this->assertIsArray($firstExtraData['normalized_source_row'] ?? null);
        $this->assertIsArray($secondExtraData['source_row'] ?? null);
        $this->assertIsArray($secondExtraData['normalized_source_row'] ?? null);
    }
    
    public function test_it_records_trigger_context_on_generic_fetch_failure_issue(): void
    {
        $this->swapTelematFetcher(new class implements TelematFetcherInterface {
            public function fetch(TelematFetchConfig $config): TelematFetchResult
            {
                throw new TelematFetchException('Generic Telemat fetch failure.');
            }
        });

        $context = $this->makeExecutionContext(
            dryRun: true,
            triggeredByLabel: 'custom-trigger-label',
        );

        /** @var TelematLicenseImportOrchestrator $orchestrator */
        $orchestrator = app(TelematLicenseImportOrchestrator::class);

        try {
            $orchestrator->run($context);
            $this->fail('Une TelematFetchException était attendue.');
        } catch (TelematFetchException $exception) {
            $this->assertSame('Generic Telemat fetch failure.', $exception->getMessage());
        }

        $batch = $this->latestBatch();

        $this->assertNotNull($batch);

        $issue = $batch->issues()
            ->where('code', 'fetch_failed')
            ->first();

        $this->assertNotNull($issue);

        $issueContext = is_array($issue->context) ? $issue->context : [];

        $this->assertSame(TelematFetchException::class, $issueContext['exception_class'] ?? null);
        $this->assertSame('ffbi_telemat', $issueContext['source'] ?? null);
        $this->assertSame('manual', $issueContext['trigger_type'] ?? null);
        $this->assertSame('custom-trigger-label', $issueContext['triggered_by'] ?? null);
        $this->assertArrayHasKey('triggered_by_user_id', $issueContext);
        $this->assertNull($issueContext['triggered_by_user_id']);
        $this->assertTrue((bool) ($issueContext['dry_run'] ?? false));
    }

    private function latestBatch(): ?LicenseImportBatch
    {
        return LicenseImportBatch::query()
            ->where('source', 'ffbi_telemat')
            ->latest('id')
            ->first();
    }
}