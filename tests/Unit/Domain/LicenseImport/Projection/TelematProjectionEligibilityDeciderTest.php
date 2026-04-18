<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\LicenseImport\Projection;

use App\Domain\LicenseImport\DTO\LicenseImportExecutionContext;
use App\Domain\LicenseImport\Enums\TriggerType;
use App\Domain\LicenseImport\Projection\TelematProjectionDataInspector;
use App\Domain\LicenseImport\Projection\TelematProjectionEligibilityDecider;
use App\Models\LicenseImportBatch;
use App\Models\LicenseImportSnapshot;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class TelematProjectionEligibilityDeciderTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_skips_when_batch_is_not_active(): void
    {
        $decider = $this->makeDecider();

        $batch = LicenseImportBatch::query()->create([
            'source' => 'ffbi_telemat',
            'status' => 'validated_comparative',
            'is_active' => false,
            'trigger_type' => TriggerType::Manual,
            'triggered_by_label' => 'phpunit',
            'triggered_by_user_id' => null,
            'raw_rows_count' => 0,
            'valid_rows_count' => 0,
            'invalid_rows_count' => 0,
            'error_count' => 0,
            'warning_count' => 0,
            'source_fingerprint' => 'test-fingerprint-1',
            'source_columns' => [],
            'summary' => [],
            'meta' => [],
            'started_at' => now(),
            'finished_at' => now(),
        ]);

        LicenseImportSnapshot::query()->create([
            'import_batch_id' => $batch->id,
            'row_index' => 1,
            'source_row_hash' => 'hash-batch-inactive',
            'is_valid' => true,
            'source_license_number' => 'LIC-001',
            'license_number' => 'LIC-001',
            'last_name' => 'Dupont',
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
                    'numero' => 'LIC-001',
                    'nom' => 'Dupont',
                    'prenom' => 'Jean',
                    'url' => 'https://www.telemat.org/FFBI/sif/./?cs=inactive',
                ],
                'normalized_source_row' => [
                    'numero' => 'LIC-001',
                    'nom' => 'Dupont',
                    'prenom' => 'Jean',
                    'url' => 'https://www.telemat.org/FFBI/sif/./?cs=inactive',
                ],
            ],
            'validation_flags' => [],
        ]);

        $context = $this->makeContext(dryRun: false);

        $result = $decider->decide($batch, $context, 'full_replace');

        $this->assertTrue($result->shouldSkip());
        $this->assertFalse($result->isPreview());
        $this->assertFalse($result->isReal());
        $this->assertSame('batch_not_activated', $result->reason);
    }

    public function test_it_skips_when_context_is_dry_run(): void
    {
        $decider = $this->makeDecider();

        $batch = LicenseImportBatch::query()->create([
            'source' => 'ffbi_telemat',
            'status' => 'activated',
            'is_active' => true,
            'trigger_type' => TriggerType::Manual,
            'triggered_by_label' => 'phpunit',
            'triggered_by_user_id' => null,
            'raw_rows_count' => 0,
            'valid_rows_count' => 0,
            'invalid_rows_count' => 0,
            'error_count' => 0,
            'warning_count' => 0,
            'source_fingerprint' => 'test-fingerprint-2',
            'source_columns' => [],
            'summary' => [],
            'meta' => [],
            'started_at' => now(),
            'finished_at' => now(),
            'activated_at' => now(),
        ]);

        LicenseImportSnapshot::query()->create([
            'import_batch_id' => $batch->id,
            'row_index' => 1,
            'source_row_hash' => 'hash-dry-run',
            'is_valid' => true,
            'source_license_number' => 'LIC-001',
            'license_number' => 'LIC-001',
            'last_name' => 'Dupont',
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
                    'numero' => 'LIC-001',
                    'nom' => 'Dupont',
                    'prenom' => 'Jean',
                    'url' => 'https://www.telemat.org/FFBI/sif/./?cs=dry-run',
                ],
                'normalized_source_row' => [
                    'numero' => 'LIC-001',
                    'nom' => 'Dupont',
                    'prenom' => 'Jean',
                    'url' => 'https://www.telemat.org/FFBI/sif/./?cs=dry-run',
                ],
            ],
            'validation_flags' => [],
        ]);

        $context = $this->makeContext(dryRun: true);

        $result = $decider->decide($batch, $context, 'full_replace');

        $this->assertTrue($result->shouldSkip());
        $this->assertFalse($result->isPreview());
        $this->assertFalse($result->isReal());
        $this->assertSame('dry_run', $result->reason);
    }

    public function test_it_allows_projection_when_batch_has_valid_snapshot_even_without_url(): void
    {
        $decider = $this->makeDecider();

        $batch = LicenseImportBatch::query()->create([
            'source' => 'ffbi_telemat',
            'status' => 'activated',
            'is_active' => true,
            'trigger_type' => TriggerType::Manual,
            'triggered_by_label' => 'phpunit',
            'triggered_by_user_id' => null,
            'raw_rows_count' => 1,
            'valid_rows_count' => 1,
            'invalid_rows_count' => 0,
            'error_count' => 0,
            'warning_count' => 0,
            'source_fingerprint' => 'test-fingerprint-3',
            'source_columns' => ['Numéro', 'Nom', 'Prénom'],
            'summary' => [],
            'meta' => [],
            'started_at' => now(),
            'finished_at' => now(),
            'activated_at' => now(),
        ]);

        LicenseImportSnapshot::query()->create([
            'import_batch_id' => $batch->id,
            'row_index' => 1,
            'source_row_hash' => 'hash-no-url',
            'is_valid' => true,
            'source_license_number' => 'LIC-001',
            'license_number' => 'LIC-001',
            'last_name' => 'Dupont',
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
                    'numero' => 'LIC-001',
                    'nom' => 'Dupont',
                    'prenom' => 'Jean',
                ],
                'normalized_source_row' => [
                    'numero' => 'LIC-001',
                    'nom' => 'Dupont',
                    'prenom' => 'Jean',
                ],
            ],
            'validation_flags' => [],
        ]);

        $context = $this->makeContext(dryRun: false);

        $result = $decider->decide($batch, $context, 'full_replace');

        $this->assertFalse($result->shouldSkip());
        $this->assertFalse($result->isPreview());
        $this->assertTrue($result->isReal());
        $this->assertNull($result->reason);
    }

    public function test_it_allows_projection_when_batch_is_active_not_dry_run_and_has_projectable_url(): void
    {
        $decider = $this->makeDecider();

        $batch = LicenseImportBatch::query()->create([
            'source' => 'ffbi_telemat',
            'status' => 'activated',
            'is_active' => true,
            'trigger_type' => TriggerType::Manual,
            'triggered_by_label' => 'phpunit',
            'triggered_by_user_id' => null,
            'raw_rows_count' => 1,
            'valid_rows_count' => 1,
            'invalid_rows_count' => 0,
            'error_count' => 0,
            'warning_count' => 0,
            'source_fingerprint' => 'test-fingerprint-4',
            'source_columns' => ['Numéro', 'Nom', 'Prénom', 'URL'],
            'summary' => [],
            'meta' => [],
            'started_at' => now(),
            'finished_at' => now(),
            'activated_at' => now(),
        ]);

        LicenseImportSnapshot::query()->create([
            'import_batch_id' => $batch->id,
            'row_index' => 1,
            'source_row_hash' => 'hash-with-url',
            'is_valid' => true,
            'source_license_number' => 'LIC-001',
            'license_number' => 'LIC-001',
            'last_name' => 'Dupont',
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
                    'numero' => 'LIC-001',
                    'nom' => 'Dupont',
                    'prenom' => 'Jean',
                    'url' => 'https://www.telemat.org/FFBI/sif/./?cs=ok',
                ],
                'normalized_source_row' => [
                    'numero' => 'LIC-001',
                    'nom' => 'Dupont',
                    'prenom' => 'Jean',
                    'url' => 'https://www.telemat.org/FFBI/sif/./?cs=ok',
                ],
            ],
            'validation_flags' => [],
        ]);

        $context = $this->makeContext(dryRun: false);

        $result = $decider->decide($batch, $context, 'full_replace');

        $this->assertFalse($result->shouldSkip());
        $this->assertFalse($result->isPreview());
        $this->assertTrue($result->isReal());
        $this->assertNull($result->reason);
    }

    private function makeContext(bool $dryRun): LicenseImportExecutionContext
    {
        return new LicenseImportExecutionContext(
            source: 'ffbi_telemat',
            triggerType: TriggerType::Manual,
            triggeredByUserId: null,
            triggeredByLabel: 'phpunit',
            requestedAt: CarbonImmutable::now(),
            dryRun: $dryRun,
        );
    }

    private function makeDecider(): TelematProjectionEligibilityDecider
    {
        return new TelematProjectionEligibilityDecider(
            new TelematProjectionDataInspector(),
        );
    }
}