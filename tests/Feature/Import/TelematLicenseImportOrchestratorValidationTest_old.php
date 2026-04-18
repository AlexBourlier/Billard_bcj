<?php

namespace Tests\Feature\Import;

use App\Domain\LicenseImport\Enums\BatchStatus;
use App\Models\LicenseImportBatch;
use App\Models\LicenseImportSnapshot;
use App\Domain\LicenseImport\Pipeline\TelematLicenseImportOrchestrator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TelematLicenseImportOrchestratorValidationTest extends TestCase
{
    use RefreshDatabase;

    private const SOURCE = 'telemat';

    public function test_it_fails_minimal_validation_when_raw_rows_count_is_below_minimum(): void
    {
        $html = $this->makeHtmlTable(
            $this->validHeaders(),
            [
                $this->validRow('LIC-001', 'Dupont', 'Jean'),
            ],
        );

        $batch = $this->runImport(
            html: $html,
            validationConfig: $this->defaultValidationConfig([
                'minimum_raw_rows_count' => 2,
            ]),
        );

        $this->assertInstanceOf(LicenseImportBatch::class, $batch);
        $this->assertBatchCounts($batch, rawRowsCount: 1, validRowsCount: 1);
        $this->assertBatchNotActivated($batch, $this->minimalValidationFailedStatus());
        $this->assertSame(1, $batch->snapshots()->count());
    }

    public function test_it_passes_minimal_validation_when_raw_rows_count_equals_minimum(): void
    {
        $html = $this->makeHtmlTable(
            $this->validHeaders(),
            [
                $this->validRow('LIC-001', 'Dupont', 'Jean'),
                $this->validRow('LIC-002', 'Martin', 'Claire'),
            ],
        );

        $batch = $this->runImport(
            html: $html,
            validationConfig: $this->defaultValidationConfig([
                'minimum_raw_rows_count' => 2,
            ]),
        );

        $this->assertInstanceOf(LicenseImportBatch::class, $batch);
        $this->assertBatchCounts($batch, rawRowsCount: 2, validRowsCount: 2);
        $this->assertBatchActivated($batch);
        $this->assertSame(2, $batch->snapshots()->count());
    }

    public function test_it_fails_minimal_validation_when_license_number_fill_rate_is_below_threshold(): void
    {
        $html = $this->makeHtmlTable(
            $this->validHeaders(),
            [
                $this->validRow('LIC-001', 'Dupont', 'Jean'),
                $this->invalidLicenseNumberRow('', 'Martin', 'Claire'),
            ],
        );

        $batch = $this->runImport(
            html: $html,
            validationConfig: $this->defaultValidationConfig([
                'required_field_fill_rate_percent' => [
                    'license_number' => 100,
                    'last_name' => 100,
                    'first_name' => 100,
                ],
            ]),
        );

        $this->assertInstanceOf(LicenseImportBatch::class, $batch);
        $this->assertBatchCounts($batch, rawRowsCount: 2);
        $this->assertBatchNotActivated($batch, $this->minimalValidationFailedStatus());

        $snapshots = $batch->snapshots()->orderBy('id')->get();

        $this->assertCount(2, $snapshots);

        $this->assertSame('LIC-001', $snapshots[0]->license_number);
        $this->assertSame('Dupont', $snapshots[0]->last_name);
        $this->assertSame('Jean', $snapshots[0]->first_name);

        $this->assertTrue(
            $snapshots[1]->license_number === null || $snapshots[1]->license_number === ''
        );
        $this->assertSame('Martin', $snapshots[1]->last_name);
        $this->assertSame('Claire', $snapshots[1]->first_name);
    }

    public function test_it_treats_whitespace_only_values_as_empty_for_required_field_fill_rate(): void
    {
        $html = $this->makeHtmlTable(
            $this->validHeaders(),
            [
                $this->validRow('LIC-001', 'Dupont', 'Jean'),
                $this->whitespaceLicenseNumberRow('Martin', 'Claire'),
            ],
        );

        $batch = $this->runImport(
            html: $html,
            validationConfig: $this->defaultValidationConfig([
                'required_field_fill_rate_percent' => [
                    'license_number' => 100,
                    'last_name' => 100,
                    'first_name' => 100,
                ],
            ]),
        );

        $this->assertInstanceOf(LicenseImportBatch::class, $batch);
        $this->assertBatchCounts($batch, rawRowsCount: 2);
        $this->assertBatchNotActivated($batch, $this->minimalValidationFailedStatus());

        $snapshots = $batch->snapshots()->orderBy('id')->get();

        $this->assertCount(2, $snapshots);
        $this->assertTrue(
            $snapshots[1]->license_number === null || $snapshots[1]->license_number === ''
        );
    }

    public function test_it_fails_minimal_validation_when_valid_ratio_is_below_threshold(): void
    {
        $html = $this->makeHtmlTable(
            $this->validHeaders(),
            [
                $this->validRow('LIC-001', 'Dupont', 'Jean'),
                $this->invalidLicenseNumberRow('', 'Martin', 'Claire'),
            ],
        );

        $batch = $this->runImport(
            html: $html,
            validationConfig: $this->defaultValidationConfig([
                'minimum_valid_ratio_percent' => 100,
                'maximum_invalid_ratio_percent' => 100,
                'required_field_fill_rate_percent' => [
                    'license_number' => 0,
                    'last_name' => 0,
                    'first_name' => 0,
                ],
            ]),
        );

        $this->assertInstanceOf(LicenseImportBatch::class, $batch);
        $this->assertBatchCounts($batch, rawRowsCount: 2, validRowsCount: 1);
        $this->assertBatchNotActivated($batch, $this->minimalValidationFailedStatus());
        $this->assertSnapshotValidity($batch, [true, false]);
    }

    public function test_it_passes_minimal_validation_when_valid_ratio_equals_threshold(): void
    {
        $html = $this->makeHtmlTable(
            $this->validHeaders(),
            [
                $this->validRow('LIC-001', 'Dupont', 'Jean'),
                $this->invalidLicenseNumberRow('', 'Martin', 'Claire'),
            ],
        );

        $batch = $this->runImport(
            html: $html,
            validationConfig: $this->defaultValidationConfig([
                'minimum_valid_ratio_percent' => 50,
                'maximum_invalid_ratio_percent' => 100,
                'required_field_fill_rate_percent' => [
                    'license_number' => 0,
                    'last_name' => 0,
                    'first_name' => 0,
                ],
            ]),
        );

        $this->assertInstanceOf(LicenseImportBatch::class, $batch);
        $this->assertBatchCounts($batch, rawRowsCount: 2, validRowsCount: 1);
        $this->assertBatchActivated($batch);
        $this->assertSnapshotValidity($batch, [true, false]);
    }

    public function test_it_fails_minimal_validation_when_invalid_ratio_exceeds_maximum(): void
    {
        $html = $this->makeHtmlTable(
            $this->validHeaders(),
            [
                $this->validRow('LIC-001', 'Dupont', 'Jean'),
                $this->invalidLicenseNumberRow('', 'Martin', 'Claire'),
            ],
        );

        $batch = $this->runImport(
            html: $html,
            validationConfig: $this->defaultValidationConfig([
                'minimum_valid_ratio_percent' => 0,
                'maximum_invalid_ratio_percent' => 0,
                'required_field_fill_rate_percent' => [
                    'license_number' => 0,
                    'last_name' => 0,
                    'first_name' => 0,
                ],
            ]),
        );

        $this->assertInstanceOf(LicenseImportBatch::class, $batch);
        $this->assertBatchCounts($batch, rawRowsCount: 2, validRowsCount: 1);
        $this->assertBatchNotActivated($batch, $this->minimalValidationFailedStatus());
        $this->assertSnapshotValidity($batch, [true, false]);
    }

    public function test_it_passes_minimal_validation_when_invalid_ratio_equals_maximum(): void
    {
        $html = $this->makeHtmlTable(
            $this->validHeaders(),
            [
                $this->validRow('LIC-001', 'Dupont', 'Jean'),
                $this->invalidLicenseNumberRow('', 'Martin', 'Claire'),
            ],
        );

        $batch = $this->runImport(
            html: $html,
            validationConfig: $this->defaultValidationConfig([
                'minimum_valid_ratio_percent' => 0,
                'maximum_invalid_ratio_percent' => 50,
                'required_field_fill_rate_percent' => [
                    'license_number' => 0,
                    'last_name' => 0,
                    'first_name' => 0,
                ],
            ]),
        );

        $this->assertInstanceOf(LicenseImportBatch::class, $batch);
        $this->assertBatchCounts($batch, rawRowsCount: 2, validRowsCount: 1);
        $this->assertBatchActivated($batch);
        $this->assertSnapshotValidity($batch, [true, false]);
    }

    public function test_it_handles_comparative_validation_when_no_previous_active_batch_exists(): void
    {
        $html = $this->makeHtmlTable(
            $this->validHeaders(),
            [
                $this->validRow('LIC-001', 'Dupont', 'Jean'),
                $this->validRow('LIC-002', 'Martin', 'Claire'),
            ],
        );

        $batch = $this->runImport(
            html: $html,
            validationConfig: $this->defaultValidationConfig([
                'minimum_raw_rows_count' => 1,
                'minimum_valid_ratio_percent' => 100,
                'maximum_invalid_ratio_percent' => 0,
            ]),
        );

        $this->assertInstanceOf(LicenseImportBatch::class, $batch);
        $this->assertBatchCounts($batch, rawRowsCount: 2, validRowsCount: 2);
        $this->assertBatchActivated($batch);
        $this->assertDatabaseCount('license_import_batches', 1);
        $this->assertSame(2, $batch->snapshots()->count());
    }

    public function test_comparative_validation_ignores_non_active_previous_batches(): void
    {
        $this->createInactiveBatchWithSnapshots([
            $this->snapshotData('OLD-001', 'Ancien', 'Batch'),
            $this->snapshotData('OLD-002', 'Ancien', 'Batch'),
        ], [
            'raw_rows_count' => 2,
            'valid_rows_count' => 2,
        ]);

        $this->createInactiveBatchWithSnapshots([
            $this->snapshotData('OLD-003', 'Encore', 'Ancien'),
        ], [
            'raw_rows_count' => 1,
            'valid_rows_count' => 1,
        ]);

        $html = $this->makeHtmlTable(
            $this->validHeaders(),
            [
                $this->validRow('LIC-001', 'Dupont', 'Jean'),
                $this->validRow('LIC-002', 'Martin', 'Claire'),
            ],
        );

        $batch = $this->runImport(
            html: $html,
            validationConfig: $this->defaultValidationConfig([
                'minimum_raw_rows_count' => 1,
                'minimum_valid_ratio_percent' => 100,
                'maximum_invalid_ratio_percent' => 0,
            ]),
        );

        $this->assertInstanceOf(LicenseImportBatch::class, $batch);
        $this->assertBatchCounts($batch, rawRowsCount: 2, validRowsCount: 2);
        $this->assertBatchActivated($batch);
        $this->assertOnlyOneActiveBatch();
        $this->assertTrue(
            LicenseImportBatch::query()
                ->whereKey($batch->id)
                ->where('is_active', true)
                ->exists()
        );
    }

    public function test_it_fails_comparative_validation_when_row_count_drops_beyond_allowed_threshold(): void
    {
        $this->createActiveBatchWithSnapshots([
            $this->snapshotData('LIC-001', 'Dupont', 'Jean'),
            $this->snapshotData('LIC-002', 'Martin', 'Claire'),
            $this->snapshotData('LIC-003', 'Bernard', 'Luc'),
            $this->snapshotData('LIC-004', 'Petit', 'Anne'),
        ], [
            'raw_rows_count' => 4,
            'valid_rows_count' => 4,
        ]);

        $html = $this->makeHtmlTable(
            $this->validHeaders(),
            [
                $this->validRow('LIC-001', 'Dupont', 'Jean'),
                $this->validRow('LIC-002', 'Martin', 'Claire'),
            ],
        );

        $batch = $this->runImport(
            html: $html,
            validationConfig: $this->defaultValidationConfig([
                'minimum_raw_rows_count' => 1,
                'minimum_valid_ratio_percent' => 100,
                'maximum_invalid_ratio_percent' => 0,
                'comparative' => [
                    'maximum_row_count_drop_percent' => 25,
                ],
            ]),
        );

        $this->assertInstanceOf(LicenseImportBatch::class, $batch);
        $this->assertBatchCounts($batch, rawRowsCount: 2, validRowsCount: 2);
        $this->assertBatchNotActivated($batch, $this->comparativeValidationFailedStatus());
        $this->assertOnlyOneActiveBatch();

        $this->assertDatabaseHas('license_import_batches', [
            'id' => $batch->id,
            'is_active' => false,
        ]);
    }

    public function test_it_passes_comparative_validation_when_row_count_variation_is_within_allowed_threshold(): void
    {
        $this->createActiveBatchWithSnapshots([
            $this->snapshotData('LIC-001', 'Dupont', 'Jean'),
            $this->snapshotData('LIC-002', 'Martin', 'Claire'),
            $this->snapshotData('LIC-003', 'Bernard', 'Luc'),
            $this->snapshotData('LIC-004', 'Petit', 'Anne'),
        ], [
            'raw_rows_count' => 4,
            'valid_rows_count' => 4,
        ]);

        $html = $this->makeHtmlTable(
            $this->validHeaders(),
            [
                $this->validRow('LIC-001', 'Dupont', 'Jean'),
                $this->validRow('LIC-002', 'Martin', 'Claire'),
                $this->validRow('LIC-003', 'Bernard', 'Luc'),
            ],
        );

        $batch = $this->runImport(
            html: $html,
            validationConfig: $this->defaultValidationConfig([
                'minimum_raw_rows_count' => 1,
                'minimum_valid_ratio_percent' => 100,
                'maximum_invalid_ratio_percent' => 0,
                'comparative' => [
                    'maximum_row_count_drop_percent' => 25,
                ],
            ]),
        );

        $this->assertInstanceOf(LicenseImportBatch::class, $batch);
        $this->assertBatchCounts($batch, rawRowsCount: 3, validRowsCount: 3);
        $this->assertBatchActivated($batch);
        $this->assertOnlyOneActiveBatch();
    }

    public function test_it_fails_comparative_validation_when_valid_rows_drop_beyond_allowed_threshold(): void
    {
        $this->createActiveBatchWithSnapshots([
            $this->snapshotData('LIC-001', 'Dupont', 'Jean'),
            $this->snapshotData('LIC-002', 'Martin', 'Claire'),
            $this->snapshotData('LIC-003', 'Bernard', 'Luc'),
            $this->snapshotData('LIC-004', 'Petit', 'Anne'),
        ], [
            'raw_rows_count' => 4,
            'valid_rows_count' => 4,
        ]);

        $html = $this->makeHtmlTable(
            $this->validHeaders(),
            [
                $this->validRow('LIC-001', 'Dupont', 'Jean'),
                $this->validRow('LIC-002', 'Martin', 'Claire'),
                $this->invalidLicenseNumberRow('', 'Bernard', 'Luc'),
                $this->invalidLicenseNumberRow('', 'Petit', 'Anne'),
            ],
        );

        $batch = $this->runImport(
            html: $html,
            validationConfig: $this->defaultValidationConfig([
                'minimum_raw_rows_count' => 1,
                'minimum_valid_ratio_percent' => 50,
                'maximum_invalid_ratio_percent' => 50,
                'required_field_fill_rate_percent' => [
                    'license_number' => 0,
                    'last_name' => 100,
                    'first_name' => 100,
                ],
                'comparative' => [
                    'maximum_valid_rows_drop_percent' => 25,
                ],
            ]),
        );

        $this->assertInstanceOf(LicenseImportBatch::class, $batch);
        $this->assertBatchCounts($batch, rawRowsCount: 4, validRowsCount: 2);
        $this->assertBatchNotActivated($batch, $this->comparativeValidationFailedStatus());
        $this->assertSnapshotValidity($batch, [true, true, false, false]);
    }

    public function test_it_detects_massive_missing_license_numbers_compared_to_previous_active_batch(): void
    {
        $this->createActiveBatchWithSnapshots([
            $this->snapshotData('LIC-001', 'Dupont', 'Jean'),
            $this->snapshotData('LIC-002', 'Martin', 'Claire'),
            $this->snapshotData('LIC-003', 'Bernard', 'Luc'),
            $this->snapshotData('LIC-004', 'Petit', 'Anne'),
        ], [
            'raw_rows_count' => 4,
            'valid_rows_count' => 4,
        ]);

        $html = $this->makeHtmlTable(
            $this->validHeaders(),
            [
                $this->validRow('LIC-001', 'Dupont', 'Jean'),
                $this->validRow('LIC-002', 'Martin', 'Claire'),
            ],
        );

        $batch = $this->runImport(
            html: $html,
            validationConfig: $this->defaultValidationConfig([
                'minimum_raw_rows_count' => 1,
                'minimum_valid_ratio_percent' => 100,
                'maximum_invalid_ratio_percent' => 0,
                'comparative' => [
                    'maximum_missing_license_numbers_percent' => 25,
                ],
            ]),
        );

        $this->assertInstanceOf(LicenseImportBatch::class, $batch);
        $this->assertBatchCounts($batch, rawRowsCount: 2, validRowsCount: 2);
        $this->assertBatchNotActivated($batch, $this->comparativeValidationFailedStatus());
        $this->assertBatchLicenseNumbers($batch, ['LIC-001', 'LIC-002']);
    }

    private function createActiveBatchWithSnapshots(
        array $snapshotsData = [],
        array $batchOverrides = [],
    ): LicenseImportBatch {
        $batch = LicenseImportBatch::factory()->create(array_merge([
            'status' => BatchStatus::Activated,
            'is_active' => true,
            'activated_at' => now(),
            'raw_rows_count' => count($snapshotsData),
            'valid_rows_count' => count(array_filter(
                $snapshotsData,
                static fn (array $snapshot) => (bool) ($snapshot['is_valid'] ?? false)
            )),
        ], $batchOverrides));

        foreach ($snapshotsData as $snapshotData) {
            LicenseImportSnapshot::factory()
                ->for($batch)
                ->create(array_merge([
                    'license_number' => null,
                    'last_name' => null,
                    'first_name' => null,
                    'is_valid' => true,
                    'extra_data' => [],
                    'validation_flags' => [],
                ], $snapshotData));
        }

        return $batch;
    }

    private function createInactiveBatchWithSnapshots(
        array $snapshotsData = [],
        array $batchOverrides = [],
    ): LicenseImportBatch {
        $batch = LicenseImportBatch::factory()->create(array_merge([
            'status' => BatchStatus::ValidatedComparative,
            'is_active' => false,
            'activated_at' => null,
            'raw_rows_count' => count($snapshotsData),
            'valid_rows_count' => count(array_filter(
                $snapshotsData,
                static fn (array $snapshot) => (bool) ($snapshot['is_valid'] ?? false)
            )),
        ], $batchOverrides));

        foreach ($snapshotsData as $snapshotData) {
            LicenseImportSnapshot::factory()
                ->for($batch)
                ->create(array_merge([
                    'license_number' => null,
                    'last_name' => null,
                    'first_name' => null,
                    'is_valid' => true,
                    'extra_data' => [],
                    'validation_flags' => [],
                ], $snapshotData));
        }

        return $batch;
    }

    private function runImport(
        string $html,
        array $validationConfig = [],
        bool $dryRun = false,
        bool $autoActivateWhenValid = true,
        array $options = [],
    ): LicenseImportBatch {
        $orchestrator = app(TelematLicenseImportOrchestrator::class);

        return $orchestrator->run(
            html: $html,
            source: self::SOURCE,
            options: array_replace_recursive([
                'dry_run' => $dryRun,
                'auto_activate_when_valid' => $autoActivateWhenValid,
                'validation' => $this->defaultValidationConfig($validationConfig),
            ], $options),
        );
    }

    private function defaultValidationConfig(array $overrides = []): array
    {
        return array_replace_recursive([
            'minimum_raw_rows_count' => 1,
            'minimum_valid_ratio_percent' => 100,
            'maximum_invalid_ratio_percent' => 0,
            'required_field_fill_rate_percent' => [
                'license_number' => 100,
                'last_name' => 100,
                'first_name' => 100,
            ],
        ], $overrides);
    }

    private function validHeaders(): array
    {
        return ['Numéro licence', 'Nom', 'Prénom'];
    }

    private function validRow(
        string $licenseNumber = 'LIC-001',
        string $lastName = 'Dupont',
        string $firstName = 'Jean',
    ): array {
        return [$licenseNumber, $lastName, $firstName];
    }

    private function invalidLicenseNumberRow(
        string $licenseNumber = '',
        string $lastName = 'Martin',
        string $firstName = 'Claire',
    ): array {
        return [$licenseNumber, $lastName, $firstName];
    }

    private function whitespaceLicenseNumberRow(
        string $lastName = 'Martin',
        string $firstName = 'Claire',
    ): array {
        return ['   ', $lastName, $firstName];
    }

    private function snapshotData(
        string $licenseNumber,
        string $lastName,
        string $firstName,
        bool $isValid = true,
        array $overrides = [],
    ): array {
        return array_merge([
            'license_number' => $licenseNumber,
            'last_name' => $lastName,
            'first_name' => $firstName,
            'is_valid' => $isValid,
            'extra_data' => [],
            'validation_flags' => [],
        ], $overrides);
    }

    private function makeHtmlTable(array $headers, array $rows): string
    {
        $thead = '<tr>' . collect($headers)
            ->map(static fn (string $header) => '<th>' . e($header) . '</th>')
            ->implode('') . '</tr>';

        $tbody = collect($rows)
            ->map(static function (array $row): string {
                return '<tr>' . collect($row)
                    ->map(static fn ($cell) => '<td>' . e((string) $cell) . '</td>')
                    ->implode('') . '</tr>';
            })
            ->implode('');

        return <<<HTML
        <table>
            <thead>{$thead}</thead>
            <tbody>{$tbody}</tbody>
        </table>
        HTML;
    }

    private function assertBatchActivated(LicenseImportBatch $batch): void
    {
        $batch->refresh();

        $this->assertTrue($batch->is_active);
        $this->assertSame(BatchStatus::Activated, $batch->status);
        $this->assertNotNull($batch->activated_at);
    }

    private function assertBatchNotActivated(
        LicenseImportBatch $batch,
        BatchStatus $expectedStatus,
    ): void {
        $batch->refresh();

        $this->assertFalse($batch->is_active);
        $this->assertSame($expectedStatus, $batch->status);
        $this->assertNull($batch->activated_at);
    }

    private function assertBatchCounts(
        LicenseImportBatch $batch,
        int $rawRowsCount,
        ?int $validRowsCount = null,
    ): void {
        $batch->refresh();

        $this->assertSame($rawRowsCount, $batch->raw_rows_count);

        if ($validRowsCount !== null) {
            $this->assertSame($validRowsCount, $batch->valid_rows_count);
        }
    }

    private function assertOnlyOneActiveBatch(): void
    {
        $this->assertSame(
            1,
            LicenseImportBatch::query()->where('is_active', true)->count()
        );
    }

    private function assertBatchLicenseNumbers(
        LicenseImportBatch $batch,
        array $expected,
    ): void {
        $actual = $batch->snapshots()
            ->orderBy('id')
            ->pluck('license_number')
            ->filter()
            ->values()
            ->all();

        $this->assertSame($expected, $actual);
    }

    private function assertSnapshotValidity(
        LicenseImportBatch $batch,
        array $expectedValidity,
    ): void {
        $actual = $batch->snapshots()
            ->orderBy('id')
            ->pluck('is_valid')
            ->map(static fn ($value) => (bool) $value)
            ->values()
            ->all();

        $this->assertSame($expectedValidity, $actual);
    }

    private function minimalValidationFailedStatus(): BatchStatus
    {
         return BatchStatus::Failed;
    }

    private function comparativeValidationFailedStatus(): BatchStatus
    {
        return BatchStatus::Failed;
    }
}