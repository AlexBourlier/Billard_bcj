<?php

declare(strict_types=1);

namespace Tests\Integration\Domain\LicenseImport;

use App\Domain\LicenseImport\Enums\BatchStatus;
use App\Domain\LicenseImport\Fetch\Contracts\TelematFetcherInterface;
use App\Domain\LicenseImport\Fetch\Exceptions\TelematFetchException;
use App\Domain\LicenseImport\Fetch\Exceptions\TelematInvalidResponseException;
use App\Domain\LicenseImport\Fetch\Exceptions\TelematNetworkException;
use App\Domain\LicenseImport\Fetch\TelematFetchConfig;
use App\Domain\LicenseImport\Fetch\TelematFetchResult;
use App\Domain\LicenseImport\Pipeline\Exceptions\MinimalValidationFailedException;
use App\Domain\LicenseImport\Pipeline\Exceptions\TelematHtmlParseException;
use App\Domain\LicenseImport\Pipeline\TelematLicenseImportOrchestrator;
use App\Models\LicenseImportBatch;
use App\Models\LicenseImportSnapshot;
use DateTimeImmutable;

final class TelematLicenseImportOrchestratorFailureCoverageTest extends TelematLicenseImportOrchestratorTestCase
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
        $this->assertSame('ffbi_telemat', $issueContext['source'] ?? null);
        $this->assertSame('manual', $issueContext['trigger_type'] ?? null);
        $this->assertSame('phpunit-license-import-test', $issueContext['triggered_by'] ?? null);
        $this->assertFalse((bool) ($issueContext['dry_run'] ?? true));
        $this->assertSame('parse', $issueContext['failure_stage'] ?? null);

        $this->assertIssueCountersAreConsistent($batch);
        $this->assertSame(0, $batch->snapshots()->count());
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

        $snapshots = LicenseImportSnapshot::query()
            ->where('import_batch_id', $batch->getKey())
            ->orderBy('row_index')
            ->get();

        $this->assertCount(2, $snapshots);

        foreach ($snapshots as $snapshot) {
            $this->assertIsArray($snapshot->extra_data);
            $this->assertIsArray($snapshot->validation_flags);

            foreach ($snapshot->validation_flags as $flag) {
                $this->assertIsString($flag);
            }

            $extraData = $snapshot->extra_data;

            $this->assertIsArray($extraData['source_row'] ?? null);
            $this->assertIsArray($extraData['normalized_source_row'] ?? null);
        }
    }

    public function test_it_marks_dry_run_batch_as_terminal_even_when_not_activated(): void
    {
        config()->set('license_import.validation.minimal.minimum_raw_rows_count', 1);
        config()->set('license_import.validation.minimal.minimum_valid_ratio_percent', 0);
        config()->set('license_import.validation.minimal.maximum_invalid_ratio_percent', 100);
        config()->set('license_import.validation.minimal.required_field_fill_rate_percent', [
            'license_number' => 0,
            'last_name' => 0,
            'first_name' => 0,
        ]);

        $this->fakeSuccessfulHtmlResponse(<<<HTML
        <html>
            <body>
                <table id="licenses">
                    <thead>
                        <tr>
                            <th>Numéro</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Catégorie</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>LIC-001</td>
                            <td>Dupont</td>
                            <td>Jean</td>
                            <td>Senior</td>
                        </tr>
                    </tbody>
                </table>
            </body>
        </html>
        HTML);

        $context = $this->makeExecutionContext(dryRun: true);

        /** @var TelematLicenseImportOrchestrator $orchestrator */
        $orchestrator = app(TelematLicenseImportOrchestrator::class);
        $orchestrator->run($context);

        $batch = $this->latestBatch();

        $this->assertNotNull($batch);

        $batch->refresh();

        $this->assertNotSame(BatchStatus::Failed, $batch->status);
        $this->assertFalse($batch->is_active);
        $this->assertNull($batch->activated_at);
        $this->assertNotNull($batch->finished_at);
        $this->assertTrue($batch->isTerminal());
    }

    public function test_it_marks_auto_activation_disabled_batch_as_terminal_even_when_not_activated(): void
    {
        config()->set('license_import.activation.auto_activate_when_valid', false);
        config()->set('license_import.validation.minimal.minimum_raw_rows_count', 1);
        config()->set('license_import.validation.minimal.minimum_valid_ratio_percent', 0);
        config()->set('license_import.validation.minimal.maximum_invalid_ratio_percent', 100);
        config()->set('license_import.validation.minimal.required_field_fill_rate_percent', [
            'license_number' => 0,
            'last_name' => 0,
            'first_name' => 0,
        ]);

        $this->fakeSuccessfulHtmlResponse(<<<HTML
        <html>
            <body>
                <table id="licenses">
                    <thead>
                        <tr>
                            <th>Numéro</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Catégorie</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>LIC-001</td>
                            <td>Dupont</td>
                            <td>Jean</td>
                            <td>Senior</td>
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

        $batch->refresh();

        $this->assertNotSame(BatchStatus::Failed, $batch->status);
        $this->assertFalse($batch->is_active);
        $this->assertNull($batch->activated_at);
        $this->assertNotNull($batch->finished_at);
        $this->assertTrue($batch->isTerminal());
    }

    public function test_it_keeps_issue_counters_consistent_after_minimal_validation_failure(): void
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
        $this->assertBatchIsFailed($batch);
        $this->assertIssueCountersAreConsistent($batch);

        $this->assertTrue($batch->issues()->where('code', 'missing_required_field')->exists());
        $this->assertTrue($batch->issues()->where('code', 'minimum_valid_ratio_not_reached')->exists());
        $this->assertTrue($batch->issues()->where('code', 'required_field_fill_rate_below_threshold')->exists());
        $this->assertTrue($batch->issues()->where('code', 'minimal_validation_failed')->exists());
    }

    public function test_it_records_trigger_context_on_fetch_failure_issue(): void
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