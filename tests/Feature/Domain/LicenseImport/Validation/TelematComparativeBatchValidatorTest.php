<?php

declare(strict_types=1);

namespace Tests\Feature\Domain\LicenseImport\Validation;

use App\Domain\LicenseImport\Enums\TriggerType;
use App\Domain\LicenseImport\Pipeline\Exceptions\ComparativeValidationFailedException;
use App\Domain\LicenseImport\Validation\TelematComparativeBatchValidator;
use App\Models\LicenseImportBatch;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\CreatesLicenseImportExecutionContext;
use Tests\TestCase;

final class TelematComparativeBatchValidatorTest extends TestCase
{
    use RefreshDatabase;
    use CreatesLicenseImportExecutionContext;

    public function test_it_skips_when_comparative_validation_is_disabled(): void
    {
        config()->set('license_import.validation.comparative', [
            'enabled' => false,
        ]);

        $batch = $this->createBatch([
            'source' => 'telemat',
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

        self::assertSame([
            'issue_counters' => [
                'error_count' => 0,
                'warning_count' => 0,
            ],
        ], $batch->summary);

        self::assertSame(0, $batch->issues()->count());
        self::assertSame(0, $batch->error_count);
        self::assertSame(0, $batch->warning_count);
    }

    public function test_it_skips_and_updates_summary_when_no_previous_active_batch_exists(): void
    {
        config()->set('license_import.validation.comparative', [
            'enabled' => true,
        ]);

        $batch = $this->createBatch([
            'source' => 'telemat',
            'is_active' => false,
            'summary' => [
                'minimal_validation' => [
                    'raw_rows_count' => 10,
                ],
            ],
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

        self::assertSame([
            'minimal_validation' => [
                'raw_rows_count' => 10,
            ],
            'comparative_validation' => [
                'enabled' => true,
                'skipped' => true,
                'reason' => 'no_previous_active_batch',
            ],
            'issue_counters' => [
                'error_count' => 0,
                'warning_count' => 0,
            ],
        ], $batch->summary);

        self::assertSame(0, $batch->issues()->count());
        self::assertSame(0, $batch->error_count);
        self::assertSame(0, $batch->warning_count);
    }

    public function test_it_records_warning_when_total_rows_variation_reaches_warning_threshold(): void
    {
        config()->set('license_import.validation.comparative', [
            'enabled' => true,
            'total_rows_variation' => [
                'warning_percent' => 20,
                'reject_percent' => 80,
            ],
            'valid_rows_variation' => [
                'warning_percent' => 50,
                'reject_percent' => 90,
            ],
            'invalid_ratio_variation' => [
                'warning_percent' => 50,
                'reject_percent' => 90,
            ],
            'field_fill_rate_variation' => [
                'warning_percent' => 50,
                'reject_percent' => 90,
            ],
        ]);

        $previousBatch = $this->createBatch([
            'source' => 'telemat',
            'is_active' => true,
            'started_at' => CarbonImmutable::parse('2026-04-14T10:00:00+02:00'),
            'raw_rows_count' => 100,
            'valid_rows_count' => 95,
            'invalid_rows_count' => 5,
            'summary' => [
                'minimal_validation' => [
                    'required_field_fill_rates' => [
                        'license_number' => 100,
                    ],
                ],
            ],
        ]);

        $currentBatch = $this->createBatch([
            'source' => 'telemat',
            'is_active' => false,
            'started_at' => CarbonImmutable::parse('2026-04-15T10:00:00+02:00'),
            'raw_rows_count' => 130,
            'valid_rows_count' => 124,
            'invalid_rows_count' => 6,
            'summary' => [
                'minimal_validation' => [
                    'required_field_fill_rates' => [
                        'license_number' => 100,
                    ],
                ],
            ],
        ]);

        $validator = $this->makeValidator();

        $validator->validate(
            $currentBatch->fresh(),
            $this->makeLicenseImportExecutionContext(
                source: 'telemat',
                triggerType: TriggerType::Manual,
            ),
        );

        $currentBatch = $currentBatch->fresh();

        $issue = $currentBatch->issues()
            ->where('code', 'comparative_total_rows_variation_warning')
            ->first();

        self::assertNotNull($issue);

        $context = is_array($issue->context) ? $issue->context : [];

        self::assertSame('total rows count', $context['metric'] ?? null);
        self::assertSame(130, $context['current_value'] ?? null);
        self::assertSame(100, $context['previous_value'] ?? null);
        self::assertSame(30, $context['variation_percent'] ?? null);
        self::assertSame(20, $context['warning_percent'] ?? null);
        self::assertSame(80, $context['reject_percent'] ?? null);

        self::assertSame(0, $currentBatch->error_count);
        self::assertSame(1, $currentBatch->warning_count);

        self::assertSame(
            $previousBatch->getKey(),
            $currentBatch->summary['comparative_validation']['previous_batch_id']
        );
        self::assertFalse($currentBatch->summary['comparative_validation']['skipped']);
    }

    public function test_it_throws_when_total_rows_variation_reaches_reject_threshold(): void
    {
        config()->set('license_import.validation.comparative', [
            'enabled' => true,
            'total_rows_variation' => [
                'warning_percent' => 20,
                'reject_percent' => 40,
            ],
            'valid_rows_variation' => [
                'warning_percent' => 90,
                'reject_percent' => 95,
            ],
            'invalid_ratio_variation' => [
                'warning_percent' => 90,
                'reject_percent' => 95,
            ],
            'field_fill_rate_variation' => [
                'warning_percent' => 90,
                'reject_percent' => 95,
            ],
        ]);

        $previousBatch = $this->createBatch([
            'source' => 'telemat',
            'is_active' => true,
            'started_at' => CarbonImmutable::parse('2026-04-14T10:00:00+02:00'),
            'raw_rows_count' => 100,
            'valid_rows_count' => 100,
            'invalid_rows_count' => 0,
            'summary' => [
                'minimal_validation' => [
                    'required_field_fill_rates' => [
                        'license_number' => 100,
                    ],
                ],
            ],
        ]);

        $currentBatch = $this->createBatch([
            'source' => 'telemat',
            'is_active' => false,
            'started_at' => CarbonImmutable::parse('2026-04-15T10:00:00+02:00'),
            'raw_rows_count' => 150,
            'valid_rows_count' => 150,
            'invalid_rows_count' => 0,
            'summary' => [
                'minimal_validation' => [
                    'required_field_fill_rates' => [
                        'license_number' => 100,
                    ],
                ],
            ],
        ]);

        $validator = $this->makeValidator();

        try {
            $validator->validate(
                $currentBatch->fresh(),
                $this->makeLicenseImportExecutionContext(
                    source: 'telemat',
                    triggerType: TriggerType::Manual,
                ),
            );

            $this->fail('Une ComparativeValidationFailedException était attendue.');
        } catch (ComparativeValidationFailedException $exception) {
            self::assertSame(
                'Comparative validation failed for the current import batch.',
                $exception->getMessage(),
            );
        }

        $currentBatch = $currentBatch->fresh();

        $issue = $currentBatch->issues()
            ->where('code', 'comparative_total_rows_variation_reject')
            ->first();

        self::assertNotNull($issue);

        $context = is_array($issue->context) ? $issue->context : [];

        self::assertSame('total rows count', $context['metric'] ?? null);
        self::assertSame(150, $context['current_value'] ?? null);
        self::assertSame(100, $context['previous_value'] ?? null);
        self::assertSame(50, $context['variation_percent'] ?? null);
        self::assertSame(20, $context['warning_percent'] ?? null);
        self::assertSame(40, $context['reject_percent'] ?? null);

        self::assertSame(1, $currentBatch->error_count);
        self::assertSame(0, $currentBatch->warning_count);

        self::assertSame(
            $previousBatch->getKey(),
            $currentBatch->summary['comparative_validation']['previous_batch_id']
        );
        self::assertFalse($currentBatch->summary['comparative_validation']['skipped']);
    }

    public function test_it_compares_invalid_ratio_percent_from_batch_counts(): void
    {
        config()->set('license_import.validation.comparative', [
            'enabled' => true,
            'total_rows_variation' => [
                'warning_percent' => 100,
                'reject_percent' => 200,
            ],
            'valid_rows_variation' => [
                'warning_percent' => 100,
                'reject_percent' => 200,
            ],
            'invalid_ratio_variation' => [
                'warning_percent' => 20,
                'reject_percent' => 30,
            ],
            'field_fill_rate_variation' => [
                'warning_percent' => 100,
                'reject_percent' => 200,
            ],
        ]);

        $previousBatch = $this->createBatch([
            'source' => 'telemat',
            'is_active' => true,
            'started_at' => CarbonImmutable::parse('2026-04-14T10:00:00+02:00'),
            'raw_rows_count' => 100,
            'valid_rows_count' => 95,
            'invalid_rows_count' => 5,
            'summary' => [
                'minimal_validation' => [
                    'required_field_fill_rates' => [],
                ],
            ],
        ]);

        $currentBatch = $this->createBatch([
            'source' => 'telemat',
            'is_active' => false,
            'started_at' => CarbonImmutable::parse('2026-04-15T10:00:00+02:00'),
            'raw_rows_count' => 100,
            'valid_rows_count' => 90,
            'invalid_rows_count' => 10,
            'summary' => [
                'minimal_validation' => [
                    'required_field_fill_rates' => [],
                ],
            ],
        ]);

        $validator = $this->makeValidator();

        try {
            $validator->validate(
                $currentBatch->fresh(),
                $this->makeLicenseImportExecutionContext(
                    source: 'telemat',
                    triggerType: TriggerType::Manual,
                ),
            );

            $this->fail('Une ComparativeValidationFailedException était attendue.');
        } catch (ComparativeValidationFailedException $exception) {
            self::assertSame(
                'Comparative validation failed for the current import batch.',
                $exception->getMessage(),
            );
        }

        $currentBatch = $currentBatch->fresh();

        $issue = $currentBatch->issues()
            ->where('code', 'comparative_invalid_ratio_variation_reject')
            ->first();

        self::assertNotNull($issue);

        $context = is_array($issue->context) ? $issue->context : [];

        self::assertSame('invalid ratio percent', $context['metric'] ?? null);
        self::assertSame(10, $context['current_value'] ?? null);
        self::assertSame(5, $context['previous_value'] ?? null);
        self::assertSame(100, $context['variation_percent'] ?? null);
        self::assertSame(20, $context['warning_percent'] ?? null);
        self::assertSame(30, $context['reject_percent'] ?? null);

        self::assertSame(1, $currentBatch->error_count);
        self::assertSame(0, $currentBatch->warning_count);

        self::assertSame(
            $previousBatch->getKey(),
            $currentBatch->summary['comparative_validation']['previous_batch_id']
        );
    }

    public function test_it_compares_required_field_fill_rates_present_in_both_batches(): void
    {
        config()->set('license_import.validation.comparative', [
            'enabled' => true,
            'total_rows_variation' => [
                'warning_percent' => 100,
                'reject_percent' => 200,
            ],
            'valid_rows_variation' => [
                'warning_percent' => 100,
                'reject_percent' => 200,
            ],
            'invalid_ratio_variation' => [
                'warning_percent' => 100,
                'reject_percent' => 200,
            ],
            'field_fill_rate_variation' => [
                'warning_percent' => 10,
                'reject_percent' => 50,
            ],
        ]);

        $previousBatch = $this->createBatch([
            'source' => 'telemat',
            'is_active' => true,
            'started_at' => CarbonImmutable::parse('2026-04-14T10:00:00+02:00'),
            'raw_rows_count' => 100,
            'valid_rows_count' => 100,
            'invalid_rows_count' => 0,
            'summary' => [
                'minimal_validation' => [
                    'required_field_fill_rates' => [
                        'license_number' => 100,
                        'last_name' => 100,
                    ],
                ],
            ],
        ]);

        $currentBatch = $this->createBatch([
            'source' => 'telemat',
            'is_active' => false,
            'started_at' => CarbonImmutable::parse('2026-04-15T10:00:00+02:00'),
            'raw_rows_count' => 100,
            'valid_rows_count' => 100,
            'invalid_rows_count' => 0,
            'summary' => [
                'minimal_validation' => [
                    'required_field_fill_rates' => [
                        'license_number' => 100,
                        'last_name' => 80,
                        'new_field_only_current' => 50,
                    ],
                ],
            ],
        ]);

        $validator = $this->makeValidator();

        $validator->validate(
            $currentBatch->fresh(),
            $this->makeLicenseImportExecutionContext(
                source: 'telemat',
                triggerType: TriggerType::Manual,
            ),
        );

        $currentBatch = $currentBatch->fresh();

        $issue = $currentBatch->issues()
            ->where('code', 'comparative_field_fill_rate_variation_last_name_warning')
            ->first();

        self::assertNotNull($issue);

        $context = is_array($issue->context) ? $issue->context : [];

        self::assertSame('field fill rate "last_name"', $context['metric'] ?? null);
        self::assertSame(80, $context['current_value'] ?? null);
        self::assertSame(100, $context['previous_value'] ?? null);
        self::assertSame(20, $context['variation_percent'] ?? null);
        self::assertSame(10, $context['warning_percent'] ?? null);
        self::assertSame(50, $context['reject_percent'] ?? null);

        self::assertSame(0, $currentBatch->error_count);
        self::assertSame(1, $currentBatch->warning_count);

        self::assertSame([
            'license_number' => 100,
            'last_name' => 80,
            'new_field_only_current' => 50,
        ], $currentBatch->summary['comparative_validation']['current']['required_field_fill_rates']);

        self::assertSame([
            'license_number' => 100,
            'last_name' => 100,
        ], $currentBatch->summary['comparative_validation']['previous']['required_field_fill_rates']);
    }

    public function test_it_uses_latest_active_batch_of_same_source_as_previous_reference(): void
    {
        config()->set('license_import.validation.comparative', [
            'enabled' => true,
            'total_rows_variation' => [
                'warning_percent' => 10,
                'reject_percent' => 20,
            ],
            'valid_rows_variation' => [
                'warning_percent' => 100,
                'reject_percent' => 200,
            ],
            'invalid_ratio_variation' => [
                'warning_percent' => 100,
                'reject_percent' => 200,
            ],
            'field_fill_rate_variation' => [
                'warning_percent' => 100,
                'reject_percent' => 200,
            ],
        ]);

        $olderActiveBatch = $this->createBatch([
            'source' => 'telemat',
            'is_active' => true,
            'started_at' => CarbonImmutable::parse('2026-04-10T10:00:00+02:00'),
            'raw_rows_count' => 50,
            'valid_rows_count' => 50,
            'invalid_rows_count' => 0,
            'summary' => [
                'minimal_validation' => [
                    'required_field_fill_rates' => [],
                ],
            ],
        ]);

        $latestActiveBatch = $this->createBatch([
            'source' => 'telemat',
            'is_active' => true,
            'started_at' => CarbonImmutable::parse('2026-04-14T10:00:00+02:00'),
            'raw_rows_count' => 100,
            'valid_rows_count' => 100,
            'invalid_rows_count' => 0,
            'summary' => [
                'minimal_validation' => [
                    'required_field_fill_rates' => [],
                ],
            ],
        ]);

        $otherSourceBatch = $this->createBatch([
            'source' => 'other-source',
            'is_active' => true,
            'started_at' => CarbonImmutable::parse('2026-04-15T09:00:00+02:00'),
            'raw_rows_count' => 999,
            'valid_rows_count' => 999,
            'invalid_rows_count' => 0,
            'summary' => [
                'minimal_validation' => [
                    'required_field_fill_rates' => [],
                ],
            ],
        ]);

        $currentBatch = $this->createBatch([
            'source' => 'telemat',
            'is_active' => false,
            'started_at' => CarbonImmutable::parse('2026-04-15T10:00:00+02:00'),
            'raw_rows_count' => 115,
            'valid_rows_count' => 115,
            'invalid_rows_count' => 0,
            'summary' => [
                'minimal_validation' => [
                    'required_field_fill_rates' => [],
                ],
            ],
        ]);

        $validator = $this->makeValidator();

        $validator->validate(
            $currentBatch->fresh(),
            $this->makeLicenseImportExecutionContext(
                source: 'telemat',
                triggerType: TriggerType::Manual,
            ),
        );

        $currentBatch = $currentBatch->fresh();

        $issue = $currentBatch->issues()
            ->where('code', 'comparative_total_rows_variation_warning')
            ->first();

        self::assertNotNull($issue);

        $context = is_array($issue->context) ? $issue->context : [];

        self::assertSame(100, $context['previous_value'] ?? null);
        self::assertSame(115, $context['current_value'] ?? null);
        self::assertSame(15, $context['variation_percent'] ?? null);

        self::assertSame(
            $latestActiveBatch->getKey(),
            $currentBatch->summary['comparative_validation']['previous_batch_id']
        );
        self::assertNotSame(
            $olderActiveBatch->getKey(),
            $currentBatch->summary['comparative_validation']['previous_batch_id']
        );
        self::assertNotSame(
            $otherSourceBatch->getKey(),
            $currentBatch->summary['comparative_validation']['previous_batch_id']
        );
    }

    private function makeValidator(): TelematComparativeBatchValidator
    {
        return new TelematComparativeBatchValidator(
            app(\App\Domain\LicenseImport\Pipeline\TelematIssueRecorder::class),
            app(\App\Domain\LicenseImport\Pipeline\TelematBatchLifecycleManager::class),
            app(\App\Domain\LicenseImport\Pipeline\TelematPipelineLogger::class),
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
            'summary' => null,
        ], $attributes));

        return $batch;
    }
}