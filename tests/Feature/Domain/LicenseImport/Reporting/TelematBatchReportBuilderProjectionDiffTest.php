<?php

declare(strict_types=1);

namespace Tests\Feature\Domain\LicenseImport\Reporting;

use App\Domain\LicenseImport\Reporting\TelematBatchReportBuilder;
use App\Models\LicenseImportBatch;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class TelematBatchReportBuilderProjectionDiffTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_includes_detailed_projection_diff_in_report(): void
    {
        $batch = LicenseImportBatch::query()->create([
            'source' => 'ffbi_telemat',
            'status' => 'activated',
            'is_active' => true,
            'trigger_type' => 'manual',
            'triggered_by_label' => 'phpunit',
            'triggered_by_user_id' => null,
            'started_at' => now(),
            'finished_at' => now(),
            'activated_at' => now(),
            'raw_rows_count' => 3,
            'valid_rows_count' => 3,
            'invalid_rows_count' => 0,
            'error_count' => 0,
            'warning_count' => 0,
            'source_fingerprint' => 'test-fingerprint',
            'source_columns' => ['Numéro', 'Nom', 'Prénom', 'URL'],
            'summary' => [
                'projection' => [
                    'executed' => true,
                    'failed' => false,
                    'reason' => null,
                    'strategy' => 'incremental',
                    'source_snapshot_count' => 3,
                    'deleted_count' => 1,
                    'inserted_count' => 1,
                    'updated_count' => 1,
                    'unchanged_count' => 1,
                    'no_op' => false,
                    'context' => null,
                    'diff' => [
                        'inserted' => [
                            [
                                'licence' => 'LIC-003',
                                'new' => [
                                    'licence' => 'LIC-003',
                                    'nom' => 'Durand',
                                    'prenom' => 'Luc',
                                    'url' => 'https://www.telemat.org/FFBI/sif/./?cs=diff-3',
                                ],
                            ],
                        ],
                        'updated' => [
                            [
                                'licence' => 'LIC-002',
                                'old' => [
                                    'licence' => 'LIC-002',
                                    'nom' => 'Martin',
                                    'prenom' => 'Paul',
                                    'url' => 'https://www.telemat.org/FFBI/sif/./?cs=diff-2',
                                ],
                                'new' => [
                                    'licence' => 'LIC-002',
                                    'nom' => 'Martin',
                                    'prenom' => 'Pierre',
                                    'url' => 'https://www.telemat.org/FFBI/sif/./?cs=diff-2b',
                                ],
                            ],
                        ],
                        'deleted' => [
                            [
                                'licence' => 'LIC-004',
                            ],
                        ],
                    ],
                ],
            ],
            'meta' => [
                'execution' => [
                    'result' => 'succeeded',
                    'final_outcome' => 'activated',
                ],
            ],
        ]);

        $builder = app(TelematBatchReportBuilder::class);

        $report = $builder->build($batch);

        $this->assertArrayHasKey('projection', $report);
        $this->assertArrayHasKey('diff', $report['projection']);

        $this->assertSame([
            'inserted' => [
                [
                    'licence' => 'LIC-003',
                    'new' => [
                        'licence' => 'LIC-003',
                        'nom' => 'Durand',
                        'prenom' => 'Luc',
                        'url' => 'https://www.telemat.org/FFBI/sif/./?cs=diff-3',
                    ],
                ],
            ],
            'updated' => [
                [
                    'licence' => 'LIC-002',
                    'old' => [
                        'licence' => 'LIC-002',
                        'nom' => 'Martin',
                        'prenom' => 'Paul',
                        'url' => 'https://www.telemat.org/FFBI/sif/./?cs=diff-2',
                    ],
                    'new' => [
                        'licence' => 'LIC-002',
                        'nom' => 'Martin',
                        'prenom' => 'Pierre',
                        'url' => 'https://www.telemat.org/FFBI/sif/./?cs=diff-2b',
                    ],
                ],
            ],
            'deleted' => [
                [
                    'licence' => 'LIC-004',
                ],
            ],
        ], $report['projection']['diff']);
    }
}