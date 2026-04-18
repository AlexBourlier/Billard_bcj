<?php

declare(strict_types=1);

namespace Tests\Integration\Domain\LicenseImport;

use App\Domain\LicenseImport\Pipeline\TelematLicenseImportOrchestrator;
use Illuminate\Support\Facades\Http;

final class TelematIncrementalProjectionPreviewTest extends TelematLicenseImportOrchestratorTestCase
{
    public function test_it_builds_a_projection_preview_in_dry_run_without_modifying_licencies(): void
    {
        config()->set('license_import.projection.strategy', 'incremental');

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

        Http::fake([
            '*' => Http::sequence()
                ->push(
                    $this->makeHtml([
                        ['LIC-001', 'Dupont', 'Jean', 'https://www.telemat.org/FFBI/sif/./?cs=preview-1'],
                        ['LIC-002', 'Martin', 'Paul', 'https://www.telemat.org/FFBI/sif/./?cs=preview-2'],
                    ]),
                    200,
                    ['Content-Type' => 'text/html; charset=UTF-8']
                )
                ->push(
                    $this->makeHtml([
                        ['LIC-001', 'Dupont', 'Jean', 'https://www.telemat.org/FFBI/sif/./?cs=preview-1'], // unchanged
                        ['LIC-002', 'Martin', 'Pierre', 'https://www.telemat.org/FFBI/sif/./?cs=preview-2b'], // updated
                        ['LIC-003', 'Durand', 'Luc', 'https://www.telemat.org/FFBI/sif/./?cs=preview-3'], // inserted
                    ]),
                    200,
                    ['Content-Type' => 'text/html; charset=UTF-8']
                ),
        ]);

        /** @var TelematLicenseImportOrchestrator $orchestrator */
        $orchestrator = app(TelematLicenseImportOrchestrator::class);

        $batch1 = $orchestrator->run($this->makeExecutionContext(dryRun: false));
        $batch1->refresh();

        $this->assertTrue($batch1->is_active);
        $this->assertDatabaseCount('licencies', 2);

        $this->assertDatabaseHas('licencies', [
            'licence' => 'LIC-001',
            'nom' => 'Dupont',
            'prenom' => 'Jean',
            'url' => 'https://www.telemat.org/FFBI/sif/./?cs=preview-1',
        ]);

        $this->assertDatabaseHas('licencies', [
            'licence' => 'LIC-002',
            'nom' => 'Martin',
            'prenom' => 'Paul',
            'url' => 'https://www.telemat.org/FFBI/sif/./?cs=preview-2',
        ]);

        /** @var TelematLicenseImportOrchestrator $orchestrator */
        $orchestrator = app(TelematLicenseImportOrchestrator::class);

        $batch2 = $orchestrator->run($this->makeExecutionContext(dryRun: true));
        $batch2->refresh();

        $summary = is_array($batch2->summary) ? $batch2->summary : [];
        $projection = is_array($summary['projection'] ?? null) ? $summary['projection'] : [];
        $preview = is_array($projection['preview'] ?? null) ? $projection['preview'] : [];

        $this->assertFalse($batch2->is_active);

        $this->assertSame(3, $batch2->raw_rows_count);
        $this->assertSame(3, $batch2->valid_rows_count);
        $this->assertSame(0, $batch2->invalid_rows_count);

        $this->assertFalse((bool) ($projection['executed'] ?? true));
        $this->assertFalse((bool) ($projection['failed'] ?? true));
        $this->assertSame('dry_run', $projection['reason'] ?? null);
        $this->assertSame('incremental', $projection['strategy'] ?? null);

        $this->assertSame(3, $projection['source_snapshot_count'] ?? null);
        $this->assertSame(0, $projection['deleted_count'] ?? null);
        $this->assertSame(1, $projection['inserted_count'] ?? null);
        $this->assertSame(1, $projection['updated_count'] ?? null);
        $this->assertSame(1, $projection['unchanged_count'] ?? null);
        $this->assertFalse((bool) ($projection['no_op'] ?? true));

        $this->assertSame([
            'inserted_count' => 1,
            'updated_count' => 1,
            'deleted_count' => 0,
            'unchanged_count' => 1,
        ], $preview);

        // La table métier ne doit pas être modifiée en dry-run
        $this->assertDatabaseCount('licencies', 2);

        $this->assertDatabaseHas('licencies', [
            'licence' => 'LIC-001',
            'nom' => 'Dupont',
            'prenom' => 'Jean',
            'url' => 'https://www.telemat.org/FFBI/sif/./?cs=preview-1',
        ]);

        $this->assertDatabaseHas('licencies', [
            'licence' => 'LIC-002',
            'nom' => 'Martin',
            'prenom' => 'Paul',
            'url' => 'https://www.telemat.org/FFBI/sif/./?cs=preview-2',
        ]);

        $this->assertDatabaseMissing('licencies', [
            'licence' => 'LIC-003',
        ]);
    }

    /**
     * @param array<int, array{0:string,1:string,2:string,3:string}> $rows
     */
    private function makeHtml(array $rows): string
    {
        $body = '';

        foreach ($rows as [$licence, $nom, $prenom, $url]) {
            $body .= sprintf(
                '<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>',
                e($licence),
                e($nom),
                e($prenom),
                e($url),
            );
        }

        return <<<HTML
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
                        {$body}
                    </tbody>
                </table>
            </body>
        </html>
        HTML;
    }
}