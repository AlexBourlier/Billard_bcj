<?php

declare(strict_types=1);

namespace Tests\Integration\Domain\LicenseImport;

use App\Domain\LicenseImport\Pipeline\TelematLicenseImportOrchestrator;
use Illuminate\Support\Facades\DB;

final class TelematLicenseImportOrchestratorProjectionTest extends TelematLicenseImportOrchestratorTestCase
{
    public function test_it_does_not_project_into_licencies_when_running_in_dry_run_mode(): void
    {
        DB::table('licencies')->insert([
            'licence' => 'KEEP001',
            'nom' => 'Stable',
            'prenom' => 'Data',
            'url' => 'https://keep.example/1',
        ]);

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
                            <td>https://www.telemat.org/FFBI/sif/./?cs=dry-run-1</td>
                        </tr>
                        <tr>
                            <td>LIC-002</td>
                            <td>Martin</td>
                            <td>Claire</td>
                            <td>https://www.telemat.org/FFBI/sif/./?cs=dry-run-2</td>
                        </tr>
                    </tbody>
                </table>
            </body>
        </html>
        HTML);

        $context = $this->makeExecutionContext(dryRun: true);

        /** @var TelematLicenseImportOrchestrator $orchestrator */
        $orchestrator = app(TelematLicenseImportOrchestrator::class);

        $batch = $orchestrator->run($context);
        $batch->refresh();

        $this->assertDatabaseHas('licencies', [
            'licence' => 'KEEP001',
            'nom' => 'Stable',
            'prenom' => 'Data',
            'url' => 'https://keep.example/1',
        ]);

        $this->assertDatabaseCount('licencies', 1);

        $summary = is_array($batch->summary) ? $batch->summary : [];
        $projection = is_array($summary['projection'] ?? null) ? $summary['projection'] : [];

        $this->assertFalse((bool) ($projection['executed'] ?? true));
        $this->assertSame('dry_run', $projection['reason'] ?? null);
        $this->assertSame('full_replace', $projection['strategy'] ?? null);
        $this->assertSame(0, $projection['source_snapshot_count'] ?? null);
        $this->assertSame(0, $projection['deleted_count'] ?? null);
        $this->assertSame(0, $projection['inserted_count'] ?? null);
    }

    public function test_it_projects_into_licencies_when_batch_is_activated_and_not_dry_run(): void
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
                            <td>https://www.telemat.org/FFBI/sif/./?cs=run-1</td>
                        </tr>
                        <tr>
                            <td>LIC-002</td>
                            <td>Martin</td>
                            <td>Claire</td>
                            <td>https://www.telemat.org/FFBI/sif/./?cs=run-2</td>
                        </tr>
                    </tbody>
                </table>
            </body>
        </html>
        HTML);

        $context = $this->makeExecutionContext(dryRun: false);

        /** @var TelematLicenseImportOrchestrator $orchestrator */
        $orchestrator = app(TelematLicenseImportOrchestrator::class);

        $batch = $orchestrator->run($context);
        $batch->refresh();

        $this->assertTrue($batch->is_active);
        $this->assertSame(2, $batch->valid_rows_count);
        $this->assertSame(0, $batch->invalid_rows_count);

        $this->assertDatabaseCount('licencies', 2);

        $this->assertDatabaseHas('licencies', [
            'licence' => 'LIC-001',
            'nom' => 'Dupont',
            'prenom' => 'Jean',
            'url' => 'https://www.telemat.org/FFBI/sif/./?cs=run-1',
        ]);

        $this->assertDatabaseHas('licencies', [
            'licence' => 'LIC-002',
            'nom' => 'Martin',
            'prenom' => 'Claire',
            'url' => 'https://www.telemat.org/FFBI/sif/./?cs=run-2',
        ]);

        $summary = is_array($batch->summary) ? $batch->summary : [];
        $projection = is_array($summary['projection'] ?? null) ? $summary['projection'] : [];

        $this->assertTrue((bool) ($projection['executed'] ?? false));
        $this->assertNull($projection['reason'] ?? null);
        $this->assertSame('full_replace', $projection['strategy'] ?? null);
        $this->assertSame(2, $projection['source_snapshot_count'] ?? null);
        $this->assertSame(0, $projection['deleted_count'] ?? null);
        $this->assertSame(2, $projection['inserted_count'] ?? null);
    }
}