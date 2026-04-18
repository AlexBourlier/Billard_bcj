<?php

declare(strict_types=1);

namespace Tests\Integration\Domain\LicenseImport;

use App\Domain\LicenseImport\Enums\BatchStatus;
use App\Domain\LicenseImport\Pipeline\TelematLicenseImportOrchestrator;
use App\Models\LicenseImportBatch;
use App\Domain\LicenseImport\Projection\Exceptions\TelematProjectionException;
use Illuminate\Support\Facades\Schema;

final class TelematLicenseImportOrchestratorProjectionFailureTest extends TelematLicenseImportOrchestratorTestCase
{
    public function test_it_handles_projection_failure_properly(): void
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
                            <th>URL</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>LIC-001</td>
                            <td>Dupont</td>
                            <td>Jean</td>
                            <td>https://www.telemat.org/FFBI/sif/./?cs=projection-failure-1</td>
                        </tr>
                    </tbody>
                </table>
            </body>
        </html>
        HTML);

        Schema::dropIfExists('licencies');

        $context = $this->makeExecutionContext(dryRun: false);

        /** @var TelematLicenseImportOrchestrator $orchestrator */
        $orchestrator = app(TelematLicenseImportOrchestrator::class);

        try {
            $orchestrator->run($context);

            $this->fail('The orchestrator should rethrow the projection exception.');
        } catch (TelematProjectionException $exception) {
            $this->assertStringContainsString('Projection database error:', $exception->getMessage());
            $this->assertStringContainsString('licencies', $exception->getMessage());
        }

        $batch = LicenseImportBatch::query()->latest('id')->first();

        $this->assertNotNull($batch);
        $this->assertSame(BatchStatus::Failed, $batch->status);

        $meta = is_array($batch->meta) ? $batch->meta : [];
        $summary = is_array($batch->summary) ? $batch->summary : [];

        $this->assertIsArray($meta);
        $this->assertIsArray($summary);
        $this->assertNotEmpty($meta['failure'] ?? []);

        $this->assertSame(
            'pipeline_unexpected_failure',
            $meta['failure']['failure_code'] ?? null,
        );
    }
}