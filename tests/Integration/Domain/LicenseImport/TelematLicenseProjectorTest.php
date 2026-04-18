<?php

declare(strict_types=1);

namespace Tests\Integration\Domain\LicenseImport;

use App\Domain\LicenseImport\Enums\BatchStatus;
use App\Domain\LicenseImport\Enums\TriggerType;
use App\Domain\LicenseImport\Projection\TelematLicenseProjector;
use App\Models\LicenseImportBatch;
use App\Models\LicenseImportSnapshot;
use Illuminate\Support\Facades\DB;

final class TelematLicenseProjectorTest extends TelematLicenseImportOrchestratorTestCase
{
    public function test_it_projects_valid_snapshots_of_an_activated_batch_into_licencies_table(): void
    {
        $batch = LicenseImportBatch::query()->create([
            'source' => 'ffbi_telemat',
            'status' => BatchStatus::Activated,
            'is_active' => true,
            'activated_at' => now(),
            'started_at' => now(),
            'finished_at' => now(),
            'trigger_type' => TriggerType::Manual,
            'triggered_by_user_id' => null,
            'triggered_by_label' => 'phpunit-projector-test',
            'raw_rows_count' => 2,
            'valid_rows_count' => 2,
            'invalid_rows_count' => 0,
            'error_count' => 0,
            'warning_count' => 0,
            'source_fingerprint' => 'projection-test-fingerprint-1',
            'source_columns' => ['numero', 'nom', 'prenom', 'url'],
            'meta' => [],
            'summary' => [],
        ]);

        LicenseImportSnapshot::query()->create([
            'import_batch_id' => $batch->id,
            'row_index' => 1,
            'source_row_hash' => 'hash-row-1',
            'is_valid' => true,
            'source_license_number' => '191100 S',
            'license_number' => '191100 S',
            'last_name' => 'Arrouas',
            'first_name' => 'Isabelle',
            'birth_date' => null,
            'gender' => null,
            'status' => null,
            'category' => null,
            'license_type' => null,
            'season' => null,
            'source_status_label' => null,
            'source_category_label' => null,
            'extra_data' => [
                'source_row' => [
                    'url' => 'https://www.telemat.org/FFBI/sif/./?cs=test-1',
                ],
                'normalized_source_row' => [
                    'url' => 'https://www.telemat.org/FFBI/sif/./?cs=test-1',
                ],
            ],
            'validation_flags' => [],
        ]);

        LicenseImportSnapshot::query()->create([
            'import_batch_id' => $batch->id,
            'row_index' => 2,
            'source_row_hash' => 'hash-row-2',
            'is_valid' => true,
            'source_license_number' => '190399 F',
            'license_number' => '190399 F',
            'last_name' => 'Auchart',
            'first_name' => 'Thierry',
            'birth_date' => null,
            'gender' => null,
            'status' => null,
            'category' => null,
            'license_type' => null,
            'season' => null,
            'source_status_label' => null,
            'source_category_label' => null,
            'extra_data' => [
                'source_row' => [
                    'url' => 'https://www.telemat.org/FFBI/sif/./?cs=test-2',
                ],
                'normalized_source_row' => [
                    'url' => 'https://www.telemat.org/FFBI/sif/./?cs=test-2',
                ],
            ],
            'validation_flags' => [],
        ]);

        /** @var TelematLicenseProjector $projector */
        $projector = app(TelematLicenseProjector::class);

        $result = $projector->project($batch);

        $this->assertTrue($result->executed);
        $this->assertSame(2, $result->sourceSnapshotCount);
        $this->assertSame(0, $result->deletedCount);
        $this->assertSame(2, $result->insertedCount);

        $this->assertDatabaseCount('licencies', 2);

        $this->assertDatabaseHas('licencies', [
            'licence' => '191100 S',
            'nom' => 'Arrouas',
            'prenom' => 'Isabelle',
            'url' => 'https://www.telemat.org/FFBI/sif/./?cs=test-1',
        ]);

        $this->assertDatabaseHas('licencies', [
            'licence' => '190399 F',
            'nom' => 'Auchart',
            'prenom' => 'Thierry',
            'url' => 'https://www.telemat.org/FFBI/sif/./?cs=test-2',
        ]);
    }

    public function test_it_replaces_existing_rows_in_licencies_table(): void
    {
        DB::table('licencies')->insert([
            [
                'licence' => 'OLD001',
                'nom' => 'Ancien',
                'prenom' => 'Licencie',
                'url' => 'https://old.example/1',
            ],
            [
                'licence' => 'OLD002',
                'nom' => 'Ancien2',
                'prenom' => 'Licencie2',
                'url' => 'https://old.example/2',
            ],
        ]);

        $batch = LicenseImportBatch::query()->create([
            'source' => 'ffbi_telemat',
            'status' => BatchStatus::Activated,
            'is_active' => true,
            'activated_at' => now(),
            'started_at' => now(),
            'finished_at' => now(),
            'trigger_type' => TriggerType::Manual,
            'triggered_by_user_id' => null,
            'triggered_by_label' => 'phpunit-projector-test',
            'raw_rows_count' => 1,
            'valid_rows_count' => 1,
            'invalid_rows_count' => 0,
            'error_count' => 0,
            'warning_count' => 0,
            'source_fingerprint' => 'projection-test-fingerprint-2',
            'source_columns' => ['numero', 'nom', 'prenom', 'url'],
            'meta' => [],
            'summary' => [],
        ]);

        LicenseImportSnapshot::query()->create([
            'import_batch_id' => $batch->id,
            'row_index' => 1,
            'source_row_hash' => 'hash-row-new-1',
            'is_valid' => true,
            'source_license_number' => 'NEW001',
            'license_number' => 'NEW001',
            'last_name' => 'Nouveau',
            'first_name' => 'Jean',
            'birth_date' => null,
            'gender' => null,
            'status' => null,
            'category' => null,
            'license_type' => null,
            'season' => null,
            'source_status_label' => null,
            'source_category_label' => null,
            'extra_data' => [
                'source_row' => [
                    'url' => 'https://www.telemat.org/FFBI/sif/./?cs=new-1',
                ],
                'normalized_source_row' => [
                    'url' => 'https://www.telemat.org/FFBI/sif/./?cs=new-1',
                ],
            ],
            'validation_flags' => [],
        ]);

        /** @var TelematLicenseProjector $projector */
        $projector = app(TelematLicenseProjector::class);

        $result = $projector->project($batch);

        $this->assertTrue($result->executed);
        $this->assertSame(1, $result->sourceSnapshotCount);
        $this->assertSame(2, $result->deletedCount);
        $this->assertSame(1, $result->insertedCount);

        $this->assertDatabaseMissing('licencies', [
            'licence' => 'OLD001',
        ]);

        $this->assertDatabaseMissing('licencies', [
            'licence' => 'OLD002',
        ]);

        $this->assertDatabaseHas('licencies', [
            'licence' => 'NEW001',
            'nom' => 'Nouveau',
            'prenom' => 'Jean',
            'url' => 'https://www.telemat.org/FFBI/sif/./?cs=new-1',
        ]);

        $this->assertDatabaseCount('licencies', 1);
    }
}