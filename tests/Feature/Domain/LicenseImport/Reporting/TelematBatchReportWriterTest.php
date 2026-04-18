<?php

declare(strict_types=1);

namespace Tests\Feature\Domain\LicenseImport\Reporting;

use App\Domain\LicenseImport\Enums\BatchStatus;
use App\Domain\LicenseImport\Enums\IssueSeverity;
use App\Domain\LicenseImport\Enums\TriggerType;
use App\Domain\LicenseImport\Reporting\TelematBatchReportBuilder;
use App\Domain\LicenseImport\Reporting\TelematBatchReportWriter;
use App\Models\LicenseImportBatch;
use App\Models\LicenseImportIssue;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use JsonException;
use Tests\TestCase;

final class TelematBatchReportWriterTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        File::deleteDirectory(storage_path('app/license-import/reports'));

        parent::tearDown();
    }

    /**
     * @throws JsonException
     */
    public function test_it_writes_batch_report_as_json_file(): void
    {
        $startedAt = CarbonImmutable::parse('2026-04-15T10:00:00+02:00');
        $finishedAt = CarbonImmutable::parse('2026-04-15T10:02:00+02:00');

        $batch = $this->createBatch([
            'source' => 'ffbi_telemat',
            'status' => BatchStatus::ValidatedComparative->value,
            'is_active' => false,
            'trigger_type' => TriggerType::Manual->value,
            'triggered_by_user_id' => null,
            'triggered_by_label' => 'writer-test',
            'started_at' => $startedAt,
            'finished_at' => $finishedAt,
            'raw_rows_count' => 3,
            'valid_rows_count' => 2,
            'invalid_rows_count' => 1,
            'error_count' => 1,
            'warning_count' => 1,
            'summary' => [
                'minimal_validation' => [
                    'raw_rows_count' => 3,
                    'valid_rows_count' => 2,
                    'invalid_rows_count' => 1,
                    'valid_ratio_percent' => 66,
                    'invalid_ratio_percent' => 33,
                    'required_field_fill_rates' => [
                        'license_number' => 100,
                        'last_name' => 100,
                    ],
                    'duplicate_license_numbers' => [],
                ],
                'activation' => [
                    'reason' => 'dry_run',
                ],
            ],
            'meta' => [
                'execution' => [
                    'result' => 'succeeded',
                    'final_outcome' => 'dry_run',
                ],
            ],
        ]);

        $this->createIssue($batch, [
            'severity' => IssueSeverity::Warning->value,
            'code' => 'missing_required_field',
            'message' => 'Required field "first_name" is missing for row 2.',
            'row_index' => 2,
            'context' => [
                'field' => 'first_name',
                'row_index' => 2,
            ],
        ]);

        $this->createIssue($batch, [
            'severity' => IssueSeverity::Error->value,
            'code' => 'required_field_fill_rate_below_threshold',
            'message' => 'Required field fill rate for "first_name" is below the configured threshold [66% < 100%].',
            'row_index' => null,
            'context' => [
                'field' => 'first_name',
                'filled_rows_count' => 2,
                'raw_rows_count' => 3,
                'actual_fill_rate_percent' => 66,
                'minimum_fill_rate_percent' => 100,
            ],
        ]);

        $builder = app(TelematBatchReportBuilder::class);
        $writer = new TelematBatchReportWriter($builder);

        $expectedPath = storage_path('app/license-import/reports')
            . DIRECTORY_SEPARATOR
            . 'ffbi_telemat-batch-'.$batch->getKey().'.json';

        $writtenPath = $writer->write($batch->fresh());

        self::assertSame($expectedPath, $writtenPath);
        self::assertTrue(File::exists($writtenPath));

        $content = File::get($writtenPath);

        self::assertNotSame('', $content);

        $decoded = json_decode($content, true, 512, JSON_THROW_ON_ERROR);

        self::assertIsArray($decoded);
        self::assertSame(
            $builder->build($batch->fresh()),
            $decoded,
        );
    }

    /**
     * @throws JsonException
     */
    public function test_it_creates_reports_directory_if_it_does_not_exist(): void
    {
        File::deleteDirectory(storage_path('app/license-import/reports'));

        $batch = $this->createBatch([
            'source' => 'ffbi_telemat',
            'status' => BatchStatus::ValidatedComparative->value,
            'summary' => [],
            'meta' => [],
        ]);

        $writer = new TelematBatchReportWriter(
            app(TelematBatchReportBuilder::class),
        );

        $writtenPath = $writer->write($batch->fresh());

        self::assertTrue(File::isDirectory(storage_path('app/license-import/reports')));
        self::assertTrue(File::exists($writtenPath));
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
            'created_at' => CarbonImmutable::parse('2026-04-15T10:01:00+02:00'),
            'updated_at' => CarbonImmutable::parse('2026-04-15T10:01:00+02:00'),
        ], $attributes));

        return $issue;
    }
}