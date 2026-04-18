<?php

declare(strict_types=1);

namespace Tests\Feature\Domain\LicenseImport\Validation;

use App\Domain\LicenseImport\Enums\TriggerType;
use App\Domain\LicenseImport\Pipeline\Exceptions\MinimalValidationFailedException;
use App\Domain\LicenseImport\Validation\TelematMinimalBatchValidator;
use App\Models\LicenseImportBatch;
use App\Models\LicenseImportSnapshot;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\CreatesLicenseImportExecutionContext;
use Tests\TestCase;
use App\Domain\LicenseImport\Pipeline\TelematBatchLifecycleManager;
use App\Domain\LicenseImport\Pipeline\TelematIssueRecorder;
use App\Domain\LicenseImport\Pipeline\TelematPipelineLogger;

final class TelematMinimalBatchValidatorTest extends TestCase
{
    use RefreshDatabase;
    use CreatesLicenseImportExecutionContext;

    public function test_it_validates_a_nominal_batch_and_updates_summary(): void
    {
        config()->set('license_import.mapping', [
            'required_fields' => ['license_number', 'last_name'],
        ]);

        config()->set('license_import.validation.minimal', [
            'minimum_raw_rows_count' => 2,
            'minimum_valid_ratio_percent' => 100,
            'maximum_invalid_ratio_percent' => 0,
            'required_field_fill_rate_percent' => [
                'license_number' => 100,
                'last_name' => 100,
            ],
            'duplicate_license_number_policy' => 'warning',
        ]);

        $batch = $this->createBatch();

        $this->createSnapshot($batch, 0, [
            'license_number' => 'LIC-001',
            'last_name' => 'Dupont',
        ]);

        $this->createSnapshot($batch, 1, [
            'license_number' => 'LIC-002',
            'last_name' => 'Martin',
        ]);

        $validator = $this->makeValidator();

        $validator->validate(
            $batch->fresh(),
            $this->makeLicenseImportExecutionContext(
                source: 'telemat',
                triggerType: TriggerType::Manual,
            ),
        );

        $batch = $batch->fresh();

        self::assertSame(2, $batch->raw_rows_count);
        self::assertSame(2, $batch->valid_rows_count);
        self::assertSame(0, $batch->invalid_rows_count);
        self::assertSame(0, $batch->issues()->count());
        self::assertSame(0, $batch->error_count);
        self::assertSame(0, $batch->warning_count);

        self::assertSame([
            'raw_rows_count' => 2,
            'valid_rows_count' => 2,
            'invalid_rows_count' => 0,
            'valid_ratio_percent' => 100,
            'invalid_ratio_percent' => 0,
            'required_field_fill_rates' => [
                'license_number' => 100,
                'last_name' => 100,
            ],
            'duplicate_license_numbers' => [],
        ], $batch->summary['minimal_validation']);

        self::assertSame([
            'error_count' => 0,
            'warning_count' => 0,
        ], $batch->summary['issue_counters']);
    }

    public function test_it_fails_when_raw_rows_count_is_below_threshold(): void
    {
        config()->set('license_import.mapping', [
            'required_fields' => ['license_number'],
        ]);

        config()->set('license_import.validation.minimal', [
            'minimum_raw_rows_count' => 2,
            'minimum_valid_ratio_percent' => 0,
            'maximum_invalid_ratio_percent' => 100,
        ]);

        $batch = $this->createBatch();

        $this->createSnapshot($batch, 0, [
            'license_number' => 'LIC-001',
        ]);

        $validator = $this->makeValidator();

        try {
            $validator->validate(
                $batch->fresh(),
                $this->makeLicenseImportExecutionContext(
                    source: 'telemat',
                    triggerType: TriggerType::Manual,
                ),
            );

            $this->fail('Une MinimalValidationFailedException était attendue.');
        } catch (MinimalValidationFailedException $exception) {
            self::assertSame(
                'Minimal validation failed for the current import batch.',
                $exception->getMessage(),
            );
        }

        $batch = $batch->fresh();

        $issue = $batch->issues()
            ->where('code', 'minimal_raw_rows_below_threshold')
            ->first();

        self::assertNotNull($issue);

        $context = is_array($issue->context) ? $issue->context : [];

        self::assertSame(1, $context['raw_rows_count'] ?? null);
        self::assertSame(2, $context['minimum_raw_rows_count'] ?? null);
        self::assertSame('telemat', $context['source'] ?? null);

        self::assertSame(1, $batch->error_count);
        self::assertSame(0, $batch->warning_count);
    }

    public function test_it_marks_snapshot_invalid_and_records_warning_when_required_field_is_missing(): void
    {
        config()->set('license_import.mapping', [
            'required_fields' => ['license_number', 'last_name'],
        ]);

        config()->set('license_import.validation.minimal', [
            'minimum_raw_rows_count' => 1,
            'minimum_valid_ratio_percent' => 0,
            'maximum_invalid_ratio_percent' => 100,
        ]);

        $batch = $this->createBatch();

        $snapshot = $this->createSnapshot($batch, 0, [
            'license_number' => 'LIC-001',
            'last_name' => null,
        ]);

        $validator = $this->makeValidator();

        $validator->validate(
            $batch->fresh(),
            $this->makeLicenseImportExecutionContext(
                source: 'telemat',
                triggerType: TriggerType::Manual,
            ),
        );

        $snapshot = $snapshot->fresh();
        $batch = $batch->fresh();

        self::assertFalse((bool) $snapshot->is_valid);
        self::assertSame(['missing_required_field:last_name'], $snapshot->validation_flags);

        $issue = $batch->issues()
            ->where('code', 'missing_required_field')
            ->first();

        self::assertNotNull($issue);

        $context = is_array($issue->context) ? $issue->context : [];

        self::assertSame('last_name', $context['field'] ?? null);
        self::assertSame(0, $context['row_index'] ?? null);
        self::assertSame($snapshot->getKey(), $context['snapshot_id'] ?? null);

        self::assertSame(0, $batch->error_count);
        self::assertSame(1, $batch->warning_count);
    }

    public function test_it_records_duplicate_license_number_warning_without_invalidating_rows_when_policy_is_warning(): void
    {
        config()->set('license_import.mapping', [
            'required_fields' => ['license_number'],
        ]);

        config()->set('license_import.validation.minimal', [
            'minimum_raw_rows_count' => 2,
            'minimum_valid_ratio_percent' => 100,
            'maximum_invalid_ratio_percent' => 0,
            'duplicate_license_number_policy' => 'warning',
        ]);

        $batch = $this->createBatch();

        $first = $this->createSnapshot($batch, 0, [
            'license_number' => 'LIC-001',
        ]);

        $second = $this->createSnapshot($batch, 1, [
            'license_number' => 'LIC-001',
        ]);

        $validator = $this->makeValidator();

        $validator->validate(
            $batch->fresh(),
            $this->makeLicenseImportExecutionContext(
                source: 'telemat',
                triggerType: TriggerType::Manual,
            ),
        );

        $batch = $batch->fresh();

        self::assertTrue((bool) $first->fresh()->is_valid);
        self::assertTrue((bool) $second->fresh()->is_valid);

        $issue = $batch->issues()
            ->where('code', 'duplicate_license_number_warning')
            ->first();

        self::assertNotNull($issue);

        $context = is_array($issue->context) ? $issue->context : [];

        self::assertSame('LIC-001', $context['license_number'] ?? null);
        self::assertSame([0, 1], $context['row_indexes'] ?? null);
        self::assertSame('warning', $context['policy'] ?? null);

        self::assertSame(['LIC-001'], $batch->summary['minimal_validation']['duplicate_license_numbers']);
        self::assertSame(0, $batch->error_count);
        self::assertSame(1, $batch->warning_count);
    }

    public function test_it_rejects_duplicate_license_numbers_when_policy_is_reject(): void
    {
        config()->set('license_import.mapping', [
            'required_fields' => ['license_number'],
        ]);

        config()->set('license_import.validation.minimal', [
            'minimum_raw_rows_count' => 2,
            'minimum_valid_ratio_percent' => 100,
            'maximum_invalid_ratio_percent' => 100,
            'duplicate_license_number_policy' => 'reject',
        ]);

        $batch = $this->createBatch();

        $first = $this->createSnapshot($batch, 0, [
            'license_number' => 'LIC-001',
        ]);

        $second = $this->createSnapshot($batch, 1, [
            'license_number' => 'LIC-001',
        ]);

        $validator = $this->makeValidator();

        try {
            $validator->validate(
                $batch->fresh(),
                $this->makeLicenseImportExecutionContext(
                    source: 'telemat',
                    triggerType: TriggerType::Manual,
                ),
            );

            $this->fail('Une MinimalValidationFailedException était attendue.');
        } catch (MinimalValidationFailedException $exception) {
            self::assertSame(
                'Minimal validation failed for the current import batch.',
                $exception->getMessage(),
            );
        }

        $first = $first->fresh();
        $second = $second->fresh();
        $batch = $batch->fresh();

        self::assertFalse((bool) $first->is_valid);
        self::assertFalse((bool) $second->is_valid);
        self::assertContains('duplicate_license_number', $first->validation_flags);
        self::assertContains('duplicate_license_number', $second->validation_flags);

        $issue = $batch->issues()
            ->where('code', 'duplicate_license_number_rejected')
            ->first();

        self::assertNotNull($issue);

        $context = is_array($issue->context) ? $issue->context : [];

        self::assertSame('LIC-001', $context['license_number'] ?? null);
        self::assertSame([0, 1], $context['row_indexes'] ?? null);
        self::assertSame('reject', $context['policy'] ?? null);

        self::assertSame(2, $batch->error_count);
    }

    public function test_it_fails_when_required_field_fill_rate_is_below_threshold(): void
    {
        config()->set('license_import.mapping', [
            'required_fields' => ['license_number', 'last_name'],
        ]);

        config()->set('license_import.validation.minimal', [
            'minimum_raw_rows_count' => 2,
            'minimum_valid_ratio_percent' => 0,
            'maximum_invalid_ratio_percent' => 100,
            'required_field_fill_rate_percent' => [
                'last_name' => 100,
            ],
        ]);

        $batch = $this->createBatch();

        $this->createSnapshot($batch, 0, [
            'license_number' => 'LIC-001',
            'last_name' => 'Dupont',
        ]);

        $this->createSnapshot($batch, 1, [
            'license_number' => 'LIC-002',
            'last_name' => null,
        ]);

        $validator = $this->makeValidator();

        try {
            $validator->validate(
                $batch->fresh(),
                $this->makeLicenseImportExecutionContext(
                    source: 'telemat',
                    triggerType: TriggerType::Manual,
                ),
            );

            $this->fail('Une MinimalValidationFailedException était attendue.');
        } catch (MinimalValidationFailedException $exception) {
            self::assertSame(
                'Minimal validation failed for the current import batch.',
                $exception->getMessage(),
            );
        }

        $batch = $batch->fresh();

        $issue = $batch->issues()
            ->where('code', 'required_field_fill_rate_below_threshold')
            ->first();

        self::assertNotNull($issue);

        $context = is_array($issue->context) ? $issue->context : [];

        self::assertSame('last_name', $context['field'] ?? null);
        self::assertSame(1, $context['filled_rows_count'] ?? null);
        self::assertSame(2, $context['raw_rows_count'] ?? null);
        self::assertSame(50, $context['actual_fill_rate_percent'] ?? null);
        self::assertSame(100, $context['minimum_fill_rate_percent'] ?? null);

        self::assertSame(1, $batch->error_count);
    }

    private function makeValidator(): TelematMinimalBatchValidator
    {
        return new TelematMinimalBatchValidator(
            app(TelematIssueRecorder::class),
            app(TelematBatchLifecycleManager::class),
            app(TelematPipelineLogger::class),
        );
    }

    private function createBatch(array $attributes = []): LicenseImportBatch
    {
        /** @var LicenseImportBatch $batch */
        $batch = LicenseImportBatch::query()->create(array_replace([
            'source' => 'telemat',
            'status' => 'running',
            'is_active' => false,
            'activated_at' => null,
            'started_at' => CarbonImmutable::parse('2026-04-15T10:00:00+02:00'),
            'finished_at' => null,
            'trigger_type' => TriggerType::Manual->value,
            'triggered_by_user_id' => null,
            'triggered_by_label' => null,
            'raw_rows_count' => 0,
            'valid_rows_count' => 0,
            'invalid_rows_count' => 0,
            'error_count' => 0,
            'warning_count' => 0,
            'source_fingerprint' => null,
            'source_columns' => null,
            'meta' => null,
            'summary' => [],
        ], $attributes));

        return $batch;
    }

    private function createSnapshot(LicenseImportBatch $batch, int $rowIndex, array $attributes = []): LicenseImportSnapshot
    {
        /** @var LicenseImportSnapshot $snapshot */
        $snapshot = LicenseImportSnapshot::query()->create(array_replace([
            'import_batch_id' => $batch->getKey(),
            'row_index' => $rowIndex,
            'source_row_hash' => sha1('row-'.$rowIndex),
            'is_valid' => true,
            'source_license_number' => null,
            'license_number' => null,
            'last_name' => null,
            'first_name' => null,
            'birth_date' => null,
            'gender' => null,
            'status' => null,
            'category' => null,
            'license_type' => null,
            'season' => null,
            'source_status_label' => null,
            'source_category_label' => null,
            'extra_data' => null,
            'validation_flags' => [],
        ], $attributes));

        return $snapshot;
    }
}