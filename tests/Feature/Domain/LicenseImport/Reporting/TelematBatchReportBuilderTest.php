<?php

declare(strict_types=1);

namespace Tests\Feature\Domain\LicenseImport\Reporting;

use App\Domain\LicenseImport\Enums\BatchStatus;
use App\Domain\LicenseImport\Enums\IssueSeverity;
use App\Domain\LicenseImport\Enums\TriggerType;
use App\Domain\LicenseImport\Reporting\TelematBatchReportBuilder;
use App\Models\LicenseImportBatch;
use App\Models\LicenseImportIssue;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class TelematBatchReportBuilderTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_builds_a_complete_report_for_a_succeeded_batch(): void
    {
        $startedAt = CarbonImmutable::parse('2026-04-15T10:00:00+02:00');
        $finishedAt = CarbonImmutable::parse('2026-04-15T10:02:00+02:00');
        $activatedAt = CarbonImmutable::parse('2026-04-15T10:03:00+02:00');

        $batch = $this->createBatch([
            'source' => 'ffbi_telemat',
            'status' => BatchStatus::Activated->value,
            'is_active' => true,
            'trigger_type' => TriggerType::Manual->value,
            'triggered_by_user_id' => null,
            'triggered_by_label' => 'phpunit-report-test',
            'started_at' => $startedAt,
            'finished_at' => $finishedAt,
            'activated_at' => $activatedAt,
            'raw_rows_count' => 10,
            'valid_rows_count' => 9,
            'invalid_rows_count' => 1,
            'error_count' => 1,
            'warning_count' => 1,
            'summary' => [
                'minimal_validation' => [
                    'raw_rows_count' => 10,
                    'valid_rows_count' => 9,
                    'invalid_rows_count' => 1,
                    'valid_ratio_percent' => 90,
                    'invalid_ratio_percent' => 10,
                    'required_field_fill_rates' => [
                        'license_number' => 100,
                        'last_name' => 100,
                        'first_name' => 90,
                    ],
                    'duplicate_license_numbers' => [],
                ],
                'comparative_validation' => [
                    'enabled' => true,
                    'skipped' => false,
                    'previous_batch_id' => 12,
                    'current' => [
                        'raw_rows_count' => 10,
                    ],
                    'previous' => [
                        'raw_rows_count' => 11,
                    ],
                ],
                'activation' => [
                    'reason' => 'activated',
                ],
            ],
            'meta' => [
                'execution' => [
                    'result' => 'succeeded',
                    'final_outcome' => 'activated',
                ],
            ],
        ]);

        $firstIssue = $this->createIssue($batch, [
            'severity' => IssueSeverity::Warning->value,
            'code' => 'comparative_total_rows_variation_warning',
            'message' => 'Total rows variation reached warning threshold.',
            'row_index' => null,
            'context' => [
                'metric' => 'total rows count',
                'current_value' => 10,
                'previous_value' => 11,
                'variation_percent' => 9,
            ],
            'created_at' => CarbonImmutable::parse('2026-04-15T10:01:00+02:00'),
        ]);

        $secondIssue = $this->createIssue($batch, [
            'severity' => IssueSeverity::Error->value,
            'code' => 'missing_required_field',
            'message' => 'Required field "first_name" is missing for row 7.',
            'row_index' => 7,
            'context' => [
                'field' => 'first_name',
                'row_index' => 7,
                'snapshot_id' => 999,
            ],
            'created_at' => CarbonImmutable::parse('2026-04-15T10:01:30+02:00'),
        ]);

        $builder = app(TelematBatchReportBuilder::class);

        $report = $builder->build($batch->fresh());

        self::assertSame($batch->getKey(), $report['batch']['id']);
        self::assertSame('ffbi_telemat', $report['batch']['source']);
        self::assertSame(BatchStatus::Activated->value, $report['batch']['status']);
        self::assertTrue($report['batch']['is_active']);

        self::assertSame([
            'type' => TriggerType::Manual->value,
            'label' => 'phpunit-report-test',
            'user_id' => null,
        ], $report['batch']['trigger']);

        self::assertSame($startedAt->toIso8601String(), $report['batch']['started_at']);
        self::assertSame($finishedAt->toIso8601String(), $report['batch']['finished_at']);
        self::assertSame($activatedAt->toIso8601String(), $report['batch']['activated_at']);

        self::assertSame([
            'raw_rows_count' => 10,
            'valid_rows_count' => 9,
            'invalid_rows_count' => 1,
        ], $report['execution']);

        self::assertSame([
            'raw_rows_count' => 10,
            'valid_rows_count' => 9,
            'invalid_rows_count' => 1,
            'valid_ratio_percent' => 90,
            'invalid_ratio_percent' => 10,
            'required_field_fill_rates' => [
                'license_number' => 100,
                'last_name' => 100,
                'first_name' => 90,
            ],
            'duplicate_license_numbers' => [],
        ], $report['validation']['minimal']);

        self::assertSame([
            'enabled' => true,
            'skipped' => false,
            'previous_batch_id' => 12,
            'current' => [
                'raw_rows_count' => 10,
            ],
            'previous' => [
                'raw_rows_count' => 11,
            ],
        ], $report['validation']['comparative']);

        self::assertSame(1, $report['issues']['error_count']);
        self::assertSame(1, $report['issues']['warning_count']);
        self::assertCount(2, $report['issues']['items']);

        self::assertSame([
            'code' => 'comparative_total_rows_variation_warning',
            'severity' => IssueSeverity::Warning->value,
            'message' => 'Total rows variation reached warning threshold.',
            'row_index' => null,
            'context' => [
                'metric' => 'total rows count',
                'current_value' => 10,
                'previous_value' => 11,
                'variation_percent' => 9,
            ],
            'created_at' => $firstIssue->created_at?->toIso8601String(),
        ], $report['issues']['items'][0]);

        self::assertSame([
            'code' => 'missing_required_field',
            'severity' => IssueSeverity::Error->value,
            'message' => 'Required field "first_name" is missing for row 7.',
            'row_index' => 7,
            'context' => [
                'field' => 'first_name',
                'row_index' => 7,
                'snapshot_id' => 999,
            ],
            'created_at' => $secondIssue->created_at?->toIso8601String(),
        ], $report['issues']['items'][1]);

        self::assertSame([
            'activated' => true,
            'activated_at' => $activatedAt->toIso8601String(),
            'final_outcome' => 'activated',
            'reason' => 'activated',
        ], $report['activation']);

        self::assertSame([
            'success' => true,
            'blocking_failure' => false,
            'failure_reason' => null,
        ], $report['result']);
    }

    public function test_it_builds_a_failed_report_when_batch_failed(): void
    {
        $batch = $this->createBatch([
            'source' => 'ffbi_telemat',
            'status' => BatchStatus::Failed->value,
            'is_active' => false,
            'trigger_type' => TriggerType::Manual->value,
            'triggered_by_user_id' => null,
            'triggered_by_label' => 'phpunit-report-test',
            'started_at' => CarbonImmutable::parse('2026-04-15T10:00:00+02:00'),
            'finished_at' => CarbonImmutable::parse('2026-04-15T10:01:00+02:00'),
            'activated_at' => null,
            'raw_rows_count' => 2,
            'valid_rows_count' => 0,
            'invalid_rows_count' => 2,
            'error_count' => 2,
            'warning_count' => 0,
            'summary' => [
                'minimal_validation' => [
                    'raw_rows_count' => 2,
                    'valid_rows_count' => 0,
                    'invalid_rows_count' => 2,
                ],
            ],
            'meta' => [
                'execution' => [
                    'result' => 'failed',
                    'final_outcome' => 'failed',
                ],
                'failure' => [
                    'failure_stage' => 'minimal_validation',
                    'failure_code' => 'minimum_valid_ratio_not_reached',
                    'failure_message' => 'Minimal validation failed for the current import batch.',
                ],
            ],
        ]);

        $builder = app(TelematBatchReportBuilder::class);

        $report = $builder->build($batch->fresh());

        self::assertFalse($report['activation']['activated']);
        self::assertNull($report['activation']['activated_at']);
        self::assertSame('failed', $report['activation']['final_outcome']);
        self::assertNull($report['activation']['reason']);

        self::assertSame([
            'success' => false,
            'blocking_failure' => true,
            'failure_reason' => 'minimum_valid_ratio_not_reached',
        ], $report['result']);
    }

    public function test_it_includes_projection_data_in_report(): void
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
            'raw_rows_count' => 2,
            'valid_rows_count' => 2,
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
                    'strategy' => 'full_replace',
                    'source_snapshot_count' => 2,
                    'deleted_count' => 0,
                    'inserted_count' => 2,
                    'updated_count' => 0,
                    'unchanged_count' => 0,
                    'no_op' => false,
                    'context' => null,
                    'diff' => null,
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

        $this->assertSame([
            'executed' => true,
            'failed' => false,
            'reason' => null,
            'strategy' => 'full_replace',
            'source_snapshot_count' => 2,
            'deleted_count' => 0,
            'inserted_count' => 2,
            'updated_count' => 0,
            'unchanged_count' => 0,
            'no_op' => false,
            'context' => null,
            'diff' => null,
        ], $report['projection']);
    }

    private function createBatch(array $attributes = []): LicenseImportBatch
    {
        /** @var LicenseImportBatch $batch */
        $batch = LicenseImportBatch::query()->create(array_replace([
            'source' => 'ffbi_telemat',
            'status' => BatchStatus::Running->value,
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
            'meta' => [],
            'summary' => [],
        ], $attributes));

        return $batch;
    }

    private function createIssue(LicenseImportBatch $batch, array $attributes = []): LicenseImportIssue
    {
        /** @var LicenseImportIssue $issue */
        $issue = LicenseImportIssue::query()->create(array_replace([
            'import_batch_id' => $batch->getKey(),
            'severity' => IssueSeverity::Warning->value,
            'code' => 'test_issue',
            'message' => 'Test issue',
            'row_index' => null,
            'context' => [],
            'created_at' => CarbonImmutable::parse('2026-04-15T10:00:00+02:00'),
            'updated_at' => CarbonImmutable::parse('2026-04-15T10:00:00+02:00'),
        ], $attributes));

        return $issue;
    }
}