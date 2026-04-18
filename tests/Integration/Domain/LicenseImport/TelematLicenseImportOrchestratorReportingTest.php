<?php

declare(strict_types=1);

namespace Tests\Integration\Domain\LicenseImport;

use App\Domain\LicenseImport\Pipeline\TelematLicenseImportOrchestrator;
use App\Models\LicenseImportBatch;
use Illuminate\Support\Facades\File;

final class TelematLicenseImportOrchestratorReportingTest extends TelematLicenseImportOrchestratorTestCase
{
    protected function tearDown(): void
    {
        File::deleteDirectory(storage_path('app/license-import/reports'));

        parent::tearDown();
    }

    public function test_it_writes_a_report_file_after_a_successful_import(): void
    {
        config()->set('license_import.mapping.required_fields', [
            'license_number',
            'last_name',
            'first_name',
        ]);

        config()->set('license_import.validation.minimal.minimum_raw_rows_count', 1);
        config()->set('license_import.validation.minimal.minimum_valid_ratio_percent', 0);
        config()->set('license_import.validation.minimal.maximum_invalid_ratio_percent', 100);
        config()->set('license_import.validation.minimal.required_field_fill_rate_percent', []);
        config()->set('license_import.validation.minimal.duplicate_license_number_policy', 'allowed');

        config()->set('license_import.validation.comparative.enabled', false);

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

        $batch = $orchestrator->run($context);

        $batch->refresh();

        $expectedPath = storage_path(sprintf(
            'app/license-import/reports/%s-batch-%d.json',
            $batch->source,
            $batch->getKey(),
        ));

        $this->assertTrue(File::exists($expectedPath));

        $content = File::get($expectedPath);
        $this->assertNotSame('', $content);

        $decoded = json_decode($content, true);

        $this->assertIsArray($decoded);
        $this->assertSame($batch->getKey(), $decoded['batch']['id'] ?? null);
        $this->assertSame($batch->source, $decoded['batch']['source'] ?? null);
        $this->assertSame(
            is_object($batch->status) && property_exists($batch->status, 'value')
                ? $batch->status->value
                : $batch->status,
            $decoded['batch']['status'] ?? null
        );
        $this->assertSame($batch->raw_rows_count, $decoded['execution']['raw_rows_count'] ?? null);
        $this->assertSame($batch->valid_rows_count, $decoded['execution']['valid_rows_count'] ?? null);
        $this->assertSame($batch->invalid_rows_count, $decoded['execution']['invalid_rows_count'] ?? null);
        $this->assertSame($batch->error_count, $decoded['issues']['error_count'] ?? null);
        $this->assertSame($batch->warning_count, $decoded['issues']['warning_count'] ?? null);
        $this->assertTrue($decoded['result']['success'] ?? false);
        $this->assertFalse($decoded['result']['blocking_failure'] ?? true);
    }

    public function test_it_writes_a_report_file_after_a_failed_import(): void
    {
        config()->set('license_import.mapping.required_fields', [
            'license_number',
            'last_name',
            'first_name',
        ]);

        config()->set('license_import.validation.minimal.minimum_raw_rows_count', 1);
        config()->set('license_import.validation.minimal.minimum_valid_ratio_percent', 100);
        config()->set('license_import.validation.minimal.maximum_invalid_ratio_percent', 0);
        config()->set('license_import.validation.minimal.required_field_fill_rate_percent', [
            'license_number' => 100,
            'last_name' => 100,
            'first_name' => 100,
        ]);
        config()->set('license_import.validation.minimal.duplicate_license_number_policy', 'allowed');

        config()->set('license_import.validation.comparative.enabled', false);

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
        } catch (\App\Domain\LicenseImport\Pipeline\Exceptions\MinimalValidationFailedException $exception) {
            $this->assertSame(
                'Minimal validation failed for the current import batch.',
                $exception->getMessage(),
            );
        }

        $batch = LicenseImportBatch::query()
            ->where('source', $context->source)
            ->latest('id')
            ->first();

        $this->assertNotNull($batch);

        $expectedPath = storage_path(sprintf(
            'app/license-import/reports/%s-batch-%d.json',
            $batch->source,
            $batch->getKey(),
        ));

        $this->assertTrue(File::exists($expectedPath));

        $content = File::get($expectedPath);
        $this->assertNotSame('', $content);

        $decoded = json_decode($content, true);

        $this->assertIsArray($decoded);
        $this->assertSame($batch->getKey(), $decoded['batch']['id'] ?? null);
        $this->assertSame($batch->source, $decoded['batch']['source'] ?? null);
        $this->assertSame('failed', $decoded['batch']['status'] ?? null);

        $this->assertFalse($decoded['result']['success'] ?? true);
        $this->assertTrue($decoded['result']['blocking_failure'] ?? false);
        $this->assertSame(
            'minimal_validation_failed',
            $decoded['result']['failure_reason'] ?? null,
        );

        $this->assertSame(
            'failed',
            $decoded['activation']['final_outcome'] ?? null,
        );

        $this->assertGreaterThanOrEqual(1, $decoded['issues']['error_count'] ?? 0);
        $this->assertIsArray($decoded['issues']['items'] ?? null);
    }

    public function test_it_writes_a_report_file_after_a_fetch_failure(): void
    {
        $this->swapTelematFetcher(new class implements \App\Domain\LicenseImport\Fetch\Contracts\TelematFetcherInterface {
            public function fetch(
                \App\Domain\LicenseImport\Fetch\TelematFetchConfig $config
            ): \App\Domain\LicenseImport\Fetch\TelematFetchResult {
                throw new \App\Domain\LicenseImport\Fetch\Exceptions\TelematFetchException(
                    'Generic Telemat fetch failure.'
                );
            }
        });

        $context = $this->makeExecutionContext();

        /** @var TelematLicenseImportOrchestrator $orchestrator */
        $orchestrator = app(TelematLicenseImportOrchestrator::class);

        try {
            $orchestrator->run($context);
            $this->fail('Une TelematFetchException était attendue.');
        } catch (\App\Domain\LicenseImport\Fetch\Exceptions\TelematFetchException $exception) {
            $this->assertSame(
                'Generic Telemat fetch failure.',
                $exception->getMessage(),
            );
        }

        $batch = LicenseImportBatch::query()
            ->where('source', $context->source)
            ->latest('id')
            ->first();

        $this->assertNotNull($batch);

        $expectedPath = storage_path(sprintf(
            'app/license-import/reports/%s-batch-%d.json',
            $batch->source,
            $batch->getKey(),
        ));

        $this->assertTrue(File::exists($expectedPath));

        $content = File::get($expectedPath);
        $this->assertNotSame('', $content);

        $decoded = json_decode($content, true);

        $this->assertIsArray($decoded);
        $this->assertSame($batch->getKey(), $decoded['batch']['id'] ?? null);
        $this->assertSame($batch->source, $decoded['batch']['source'] ?? null);
        $this->assertSame('failed', $decoded['batch']['status'] ?? null);

        $this->assertFalse($decoded['result']['success'] ?? true);
        $this->assertTrue($decoded['result']['blocking_failure'] ?? false);
        $this->assertSame('fetch_failed', $decoded['result']['failure_reason'] ?? null);

        $this->assertSame(0, $decoded['execution']['raw_rows_count'] ?? null);
        $this->assertSame(0, $decoded['execution']['valid_rows_count'] ?? null);
        $this->assertSame(0, $decoded['execution']['invalid_rows_count'] ?? null);

        $this->assertSame(1, $decoded['issues']['error_count'] ?? null);
        $this->assertSame(0, $decoded['issues']['warning_count'] ?? null);
        $this->assertIsArray($decoded['issues']['items'] ?? null);
        $this->assertCount(1, $decoded['issues']['items']);

        $this->assertSame(
            'fetch_failed',
            $decoded['issues']['items'][0]['code'] ?? null,
        );
        $this->assertSame(
            'Generic Telemat fetch failure.',
            $decoded['issues']['items'][0]['message'] ?? null,
        );
    }

    public function test_it_writes_a_report_file_after_an_invalid_response_fetch_failure(): void
    {
        $this->swapTelematFetcher(new class implements \App\Domain\LicenseImport\Fetch\Contracts\TelematFetcherInterface {
            public function fetch(
                \App\Domain\LicenseImport\Fetch\TelematFetchConfig $config
            ): \App\Domain\LicenseImport\Fetch\TelematFetchResult {
                throw new \App\Domain\LicenseImport\Fetch\Exceptions\TelematInvalidResponseException(
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
        } catch (\App\Domain\LicenseImport\Fetch\Exceptions\TelematInvalidResponseException $exception) {
            $this->assertSame(
                'Telemat returned an invalid HTML payload.',
                $exception->getMessage(),
            );
            $this->assertSame('missing_expected_table', $exception->reason);
        }

        $batch = LicenseImportBatch::query()
            ->where('source', $context->source)
            ->latest('id')
            ->first();

        $this->assertNotNull($batch);

        $expectedPath = storage_path(sprintf(
            'app/license-import/reports/%s-batch-%d.json',
            $batch->source,
            $batch->getKey(),
        ));

        $this->assertTrue(File::exists($expectedPath));

        $content = File::get($expectedPath);
        $this->assertNotSame('', $content);

        $decoded = json_decode($content, true);

        $this->assertIsArray($decoded);
        $this->assertSame($batch->getKey(), $decoded['batch']['id'] ?? null);
        $this->assertSame($batch->source, $decoded['batch']['source'] ?? null);
        $this->assertSame('failed', $decoded['batch']['status'] ?? null);

        $this->assertFalse($decoded['result']['success'] ?? true);
        $this->assertTrue($decoded['result']['blocking_failure'] ?? false);
        $this->assertSame('fetch_invalid_response', $decoded['result']['failure_reason'] ?? null);

        $this->assertSame(0, $decoded['execution']['raw_rows_count'] ?? null);
        $this->assertSame(0, $decoded['execution']['valid_rows_count'] ?? null);
        $this->assertSame(0, $decoded['execution']['invalid_rows_count'] ?? null);

        $this->assertSame(1, $decoded['issues']['error_count'] ?? null);
        $this->assertSame(0, $decoded['issues']['warning_count'] ?? null);
        $this->assertIsArray($decoded['issues']['items'] ?? null);
        $this->assertCount(1, $decoded['issues']['items']);

        $issue = $decoded['issues']['items'][0] ?? [];

        $this->assertSame('fetch_invalid_response', $issue['code'] ?? null);
        $this->assertSame('Telemat returned an invalid HTML payload.', $issue['message'] ?? null);
        $this->assertSame('missing_expected_table', $issue['context']['reason'] ?? null);
        $this->assertSame([
            'http_status' => 200,
            'content_type' => 'text/html',
            'final_url' => 'https://example.test/licenses',
        ], $issue['context']['response_context'] ?? null);
    }

    public function test_it_writes_a_report_file_after_a_network_fetch_failure(): void
    {
        $this->swapTelematFetcher(new class implements \App\Domain\LicenseImport\Fetch\Contracts\TelematFetcherInterface {
            public function fetch(
                \App\Domain\LicenseImport\Fetch\TelematFetchConfig $config
            ): \App\Domain\LicenseImport\Fetch\TelematFetchResult {
                throw new \App\Domain\LicenseImport\Fetch\Exceptions\TelematNetworkException(
                    message: 'Network error while calling Telemat.',
                );
            }
        });

        $context = $this->makeExecutionContext();

        /** @var TelematLicenseImportOrchestrator $orchestrator */
        $orchestrator = app(TelematLicenseImportOrchestrator::class);

        try {
            $orchestrator->run($context);
            $this->fail('Une TelematNetworkException était attendue.');
        } catch (\App\Domain\LicenseImport\Fetch\Exceptions\TelematNetworkException $exception) {
            $this->assertSame(
                'Network error while calling Telemat.',
                $exception->getMessage(),
            );
        }

        $batch = LicenseImportBatch::query()
            ->where('source', $context->source)
            ->latest('id')
            ->first();

        $this->assertNotNull($batch);

        $expectedPath = storage_path(sprintf(
            'app/license-import/reports/%s-batch-%d.json',
            $batch->source,
            $batch->getKey(),
        ));

        $this->assertTrue(File::exists($expectedPath));

        $content = File::get($expectedPath);
        $this->assertNotSame('', $content);

        $decoded = json_decode($content, true);

        $this->assertIsArray($decoded);
        $this->assertSame($batch->getKey(), $decoded['batch']['id'] ?? null);
        $this->assertSame($batch->source, $decoded['batch']['source'] ?? null);
        $this->assertSame('failed', $decoded['batch']['status'] ?? null);

        $this->assertFalse($decoded['result']['success'] ?? true);
        $this->assertTrue($decoded['result']['blocking_failure'] ?? false);
        $this->assertSame('fetch_network_failure', $decoded['result']['failure_reason'] ?? null);

        $this->assertSame(0, $decoded['execution']['raw_rows_count'] ?? null);
        $this->assertSame(0, $decoded['execution']['valid_rows_count'] ?? null);
        $this->assertSame(0, $decoded['execution']['invalid_rows_count'] ?? null);

        $this->assertSame(1, $decoded['issues']['error_count'] ?? null);
        $this->assertSame(0, $decoded['issues']['warning_count'] ?? null);
        $this->assertIsArray($decoded['issues']['items'] ?? null);
        $this->assertCount(1, $decoded['issues']['items']);

        $issue = $decoded['issues']['items'][0] ?? [];

        $this->assertSame('fetch_network_failure', $issue['code'] ?? null);
        $this->assertSame('Network error while calling Telemat.', $issue['message'] ?? null);

        $this->assertIsArray($issue['context'] ?? null);
        $this->assertSame($context->source, $issue['context']['source'] ?? null);
        $this->assertSame($context->triggerType->value, $issue['context']['trigger_type'] ?? null);
    }

    private function validTelematHtml(): string
    {
        return <<<'HTML'
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
                </tbody>
            </table>
        </body>
        </html>
        HTML;
    }
}