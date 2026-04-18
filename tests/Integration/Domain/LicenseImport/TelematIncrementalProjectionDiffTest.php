<?php

declare(strict_types=1);

namespace Tests\Integration\Domain\LicenseImport;

use App\Domain\LicenseImport\Pipeline\TelematLicenseImportOrchestrator;
use Illuminate\Support\Facades\Http;

final class TelematIncrementalProjectionDiffTest extends TelematLicenseImportOrchestratorTestCase
{
    public function test_it_stores_detailed_diff_for_incremental_projection(): void
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
                        ['LIC-001', 'Dupont', 'Jean', 'https://www.telemat.org/FFBI/sif/./?cs=diff-1'],
                        ['LIC-002', 'Martin', 'Paul', 'https://www.telemat.org/FFBI/sif/./?cs=diff-2'],
                    ]),
                    200,
                    ['Content-Type' => 'text/html; charset=UTF-8']
                )
                ->push(
                    $this->makeHtml([
                        ['LIC-001', 'Dupont', 'Jean', 'https://www.telemat.org/FFBI/sif/./?cs=diff-1'],
                        ['LIC-002', 'Martin', 'Pierre', 'https://www.telemat.org/FFBI/sif/./?cs=diff-2b'],
                        ['LIC-003', 'Durand', 'Luc', 'https://www.telemat.org/FFBI/sif/./?cs=diff-3'],
                    ]),
                    200,
                    ['Content-Type' => 'text/html; charset=UTF-8']
                ),
        ]);

        /** @var TelematLicenseImportOrchestrator $orchestrator */
        $orchestrator = app(TelematLicenseImportOrchestrator::class);
        $orchestrator->run($this->makeExecutionContext(dryRun: false));

        /** @var TelematLicenseImportOrchestrator $orchestrator */
        $orchestrator = app(TelematLicenseImportOrchestrator::class);
        $batch2 = $orchestrator->run($this->makeExecutionContext(dryRun: false));
        $batch2->refresh();

        $summary = is_array($batch2->summary) ? $batch2->summary : [];
        $projection = is_array($summary['projection'] ?? null) ? $summary['projection'] : [];
        $diff = is_array($projection['diff'] ?? null) ? $projection['diff'] : [];

        $this->assertArrayHasKey('inserted', $diff);
        $this->assertArrayHasKey('updated', $diff);
        $this->assertArrayHasKey('deleted', $diff);

        $this->assertCount(1, $diff['inserted']);
        $this->assertCount(1, $diff['updated']);
        $this->assertCount(0, $diff['deleted']);

        $this->assertSame('LIC-003', $diff['inserted'][0]['licence'] ?? null);
        $this->assertSame([
            'licence' => 'LIC-003',
            'nom' => 'Durand',
            'prenom' => 'Luc',
            'url' => 'https://www.telemat.org/FFBI/sif/./?cs=diff-3',
        ], $diff['inserted'][0]['new'] ?? null);

        $this->assertSame('LIC-002', $diff['updated'][0]['licence'] ?? null);
        $this->assertSame([
            'licence' => 'LIC-002',
            'nom' => 'Martin',
            'prenom' => 'Paul',
            'url' => 'https://www.telemat.org/FFBI/sif/./?cs=diff-2',
        ], $diff['updated'][0]['old'] ?? null);

        $this->assertSame([
            'licence' => 'LIC-002',
            'nom' => 'Martin',
            'prenom' => 'Pierre',
            'url' => 'https://www.telemat.org/FFBI/sif/./?cs=diff-2b',
        ], $diff['updated'][0]['new'] ?? null);
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