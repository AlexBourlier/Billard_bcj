<?php

declare(strict_types=1);

namespace Tests\Integration\Domain\LicenseImport;

use App\Domain\LicenseImport\Enums\BatchStatus;
use App\Domain\LicenseImport\Enums\TriggerType;
use App\Domain\LicenseImport\Pipeline\TelematLicenseImportOrchestrator;
use App\Models\LicenseImportBatch;
use RuntimeException;
use Tests\Integration\Domain\LicenseImport\Concerns\InteractsWithTelematHtmlFixtures;

final class TelematLicenseImportOrchestratorValidationTest extends TelematLicenseImportOrchestratorTestCase
{
    use InteractsWithTelematHtmlFixtures;

    public function test_it_fails_minimal_validation_when_raw_rows_count_is_below_minimum(): void
    {
        config()->set('license_import.validation.minimal.minimum_raw_rows_count', 2);

        $this->fakeHtmlTable([
            ['LIC-001', 'Dupont', 'Jean', 'Senior'],
        ]);

        $batch = $this->runImportExpectingRuntimeException(
            'Minimal validation failed for the current import batch.',
        );

        $this->assertBatchCounts($batch, rawRowsCount: 1, validRowsCount: 1, invalidRowsCount: 0);
        $this->assertBatchIsFailed($batch);
        $this->assertSame(1, $batch->snapshots()->count());
        $this->assertBatchHasIssueCode($batch, 'minimal_raw_rows_below_threshold');
    }

    public function test_it_passes_minimal_validation_when_raw_rows_count_equals_minimum(): void
    {
        config()->set('license_import.validation.minimal.minimum_raw_rows_count', 2);

        $this->fakeHtmlTable([
            ['LIC-001', 'Dupont', 'Jean', 'Senior'],
            ['LIC-002', 'Martin', 'Claire', 'Junior'],
        ]);

        $batch = $this->runImportSuccessfully();

        $this->assertBatchCounts($batch, rawRowsCount: 2, validRowsCount: 2, invalidRowsCount: 0);
        $this->assertBatchActivated($batch);
        $this->assertSame(2, $batch->snapshots()->count());
    }

    public function test_it_fails_minimal_validation_when_license_number_fill_rate_is_below_threshold(): void
    {
        $this->setMinimalValidationForRatioTests(
            minimumValidRatioPercent: 0,
            maximumInvalidRatioPercent: 100,
            requiredFieldFillRatePercent: [
                'license_number' => 100,
                'last_name' => 100,
                'first_name' => 100,
            ],
        );

        $this->fakeHtmlTable([
            ['LIC-001', 'Dupont', 'Jean', 'Senior'],
            ['', 'Martin', 'Claire', 'Junior'],
        ]);

        $batch = $this->runImportExpectingRuntimeException(
            'Minimal validation failed for the current import batch.',
        );

        $this->assertBatchCounts($batch, rawRowsCount: 2, validRowsCount: 1, invalidRowsCount: 1);
        $this->assertBatchIsFailed($batch);

        $snapshots = $batch->snapshots()->orderBy('row_index')->get();

        $this->assertCount(2, $snapshots);
        $this->assertSame('LIC-001', $snapshots[0]->license_number);
        $this->assertSame('Dupont', $snapshots[0]->last_name);
        $this->assertSame('Jean', $snapshots[0]->first_name);

        $this->assertNull($snapshots[1]->license_number);
        $this->assertSame('Martin', $snapshots[1]->last_name);
        $this->assertSame('Claire', $snapshots[1]->first_name);

        $this->assertBatchHasIssueCode($batch, 'required_field_fill_rate_below_threshold');
    }

    public function test_it_treats_whitespace_only_values_as_empty_for_required_field_fill_rate(): void
    {
        $this->setMinimalValidationForRatioTests(
            minimumValidRatioPercent: 0,
            maximumInvalidRatioPercent: 100,
            requiredFieldFillRatePercent: [
                'license_number' => 100,
                'last_name' => 100,
                'first_name' => 100,
            ],
        );

        $this->fakeHtmlTable([
            ['LIC-001', 'Dupont', 'Jean', 'Senior'],
            ['   ', 'Martin', 'Claire', 'Junior'],
        ]);

        $batch = $this->runImportExpectingRuntimeException(
            'Minimal validation failed for the current import batch.',
        );

        $this->assertBatchCounts($batch, rawRowsCount: 2, validRowsCount: 1, invalidRowsCount: 1);
        $this->assertBatchIsFailed($batch);

        $snapshots = $batch->snapshots()->orderBy('row_index')->get();

        $this->assertCount(2, $snapshots);
        $this->assertNull($snapshots[1]->license_number);
        $this->assertBatchHasIssueCode($batch, 'required_field_fill_rate_below_threshold');
    }

    public function test_it_fails_minimal_validation_when_valid_ratio_is_below_threshold(): void
    {
        $this->setMinimalValidationForRatioTests(
            minimumValidRatioPercent: 100,
            maximumInvalidRatioPercent: 100,
        );

        $this->fakeHtmlTable([
            ['LIC-001', 'Dupont', 'Jean', 'Senior'],
            ['', 'Martin', 'Claire', 'Junior'],
        ]);

        $batch = $this->runImportExpectingRuntimeException(
            'Minimal validation failed for the current import batch.',
        );

        $this->assertBatchCounts($batch, rawRowsCount: 2, validRowsCount: 1, invalidRowsCount: 1);
        $this->assertBatchIsFailed($batch);
        $this->assertSnapshotValidity($batch, [true, false]);
        $this->assertBatchHasIssueCode($batch, 'minimum_valid_ratio_not_reached');
    }

    public function test_it_passes_minimal_validation_when_valid_ratio_equals_threshold(): void
    {
        $this->setMinimalValidationForRatioTests(
            minimumValidRatioPercent: 50,
            maximumInvalidRatioPercent: 100,
        );

        $this->fakeHtmlTable([
            ['LIC-001', 'Dupont', 'Jean', 'Senior'],
            ['', 'Martin', 'Claire', 'Junior'],
        ]);

        $batch = $this->runImportSuccessfully();

        $this->assertBatchCounts($batch, rawRowsCount: 2, validRowsCount: 1, invalidRowsCount: 1);
        $this->assertBatchActivated($batch);
        $this->assertSnapshotValidity($batch, [true, false]);
    }

    public function test_it_fails_minimal_validation_when_invalid_ratio_exceeds_maximum(): void
    {
        $this->setMinimalValidationForRatioTests(
            minimumValidRatioPercent: 0,
            maximumInvalidRatioPercent: 0,
        );

        $this->fakeHtmlTable([
            ['LIC-001', 'Dupont', 'Jean', 'Senior'],
            ['', 'Martin', 'Claire', 'Junior'],
        ]);

        $batch = $this->runImportExpectingRuntimeException(
            'Minimal validation failed for the current import batch.',
        );

        $this->assertBatchCounts($batch, rawRowsCount: 2, validRowsCount: 1, invalidRowsCount: 1);
        $this->assertBatchIsFailed($batch);
        $this->assertSnapshotValidity($batch, [true, false]);
        $this->assertBatchHasIssueCode($batch, 'maximum_invalid_ratio_exceeded');
    }

    public function test_it_passes_minimal_validation_when_invalid_ratio_equals_maximum(): void
    {
        $this->setMinimalValidationForRatioTests(
            minimumValidRatioPercent: 0,
            maximumInvalidRatioPercent: 50,
        );

        $this->fakeHtmlTable([
            ['LIC-001', 'Dupont', 'Jean', 'Senior'],
            ['', 'Martin', 'Claire', 'Junior'],
        ]);

        $batch = $this->runImportSuccessfully();

        $this->assertBatchCounts($batch, rawRowsCount: 2, validRowsCount: 1, invalidRowsCount: 1);
        $this->assertBatchActivated($batch);
        $this->assertSnapshotValidity($batch, [true, false]);
    }

    public function test_it_handles_comparative_validation_when_no_previous_active_batch_exists(): void
    {
        $this->enableComparativeValidation();

        $this->fakeHtmlTable([
            ['LIC-001', 'Dupont', 'Jean', 'Senior'],
            ['LIC-002', 'Martin', 'Claire', 'Junior'],
        ]);

        $batch = $this->runImportSuccessfully();
        $this->assertBatchActivated($batch);

        $this->assertBatchCounts($batch, rawRowsCount: 2, validRowsCount: 2, invalidRowsCount: 0);
        $this->assertDatabaseCount('license_import_batches', 1);
        $this->assertSame(2, $batch->snapshots()->count());

        $summary = is_array($batch->summary) ? $batch->summary : [];
        $comparative = is_array($summary['comparative_validation'] ?? null)
            ? $summary['comparative_validation']
            : [];

        $this->assertTrue((bool) ($comparative['enabled'] ?? false));
        $this->assertTrue((bool) ($comparative['skipped'] ?? false));
        $this->assertSame('no_previous_active_batch', $comparative['reason'] ?? null);
    }

    public function test_comparative_validation_ignores_non_active_previous_batches(): void
    {
        $this->createInactiveBatchWithSummary(
            rawRowsCount: 2,
            validRowsCount: 2,
            invalidRowsCount: 0,
            requiredFieldFillRates: [
                'license_number' => 100,
                'last_name' => 100,
                'first_name' => 100,
            ],
        );

        $this->createInactiveBatchWithSummary(
            rawRowsCount: 1,
            validRowsCount: 1,
            invalidRowsCount: 0,
            requiredFieldFillRates: [
                'license_number' => 100,
                'last_name' => 100,
                'first_name' => 100,
            ],
        );

        $this->enableComparativeValidation();

        $this->fakeHtmlTable([
            ['LIC-001', 'Dupont', 'Jean', 'Senior'],
            ['LIC-002', 'Martin', 'Claire', 'Junior'],
        ]);

        $batch = $this->runImportSuccessfully();

        $this->assertBatchCounts($batch, rawRowsCount: 2, validRowsCount: 2, invalidRowsCount: 0);
        $this->assertBatchActivated($batch);
        $this->assertOnlyOneActiveBatch();
    }

    public function test_it_fails_comparative_validation_when_row_count_variation_reaches_reject_threshold(): void
    {
        $this->createActiveBatchWithSummary(
            rawRowsCount: 4,
            validRowsCount: 4,
            invalidRowsCount: 0,
            requiredFieldFillRates: [
                'license_number' => 100,
                'last_name' => 100,
                'first_name' => 100,
            ],
        );

        $this->enableComparativeValidation(
            totalRowsVariation: ['warning_percent' => 10, 'reject_percent' => 50],
        );

        $this->fakeHtmlTable([
            ['LIC-001', 'Dupont', 'Jean', 'Senior'],
            ['LIC-002', 'Martin', 'Claire', 'Junior'],
        ]);

        $batch = $this->runImportExpectingRuntimeException(
            'Comparative validation failed for the current import batch.',
        );

        $this->assertBatchCounts($batch, rawRowsCount: 2, validRowsCount: 2, invalidRowsCount: 0);
        $this->assertBatchIsFailed($batch);
        $this->assertBatchHasIssueCode($batch, 'comparative_total_rows_variation_reject');
        $this->assertOnlyOneActiveBatch();
    }

    public function test_it_passes_comparative_validation_when_row_count_variation_stays_below_reject_threshold(): void
    {
        $this->createActiveBatchWithSummary(
            rawRowsCount: 4,
            validRowsCount: 4,
            invalidRowsCount: 0,
            requiredFieldFillRates: [
                'license_number' => 100,
                'last_name' => 100,
                'first_name' => 100,
            ],
        );

        $this->enableComparativeValidation(
            totalRowsVariation: ['warning_percent' => 10, 'reject_percent' => 26],
        );

        $this->fakeHtmlTable([
            ['LIC-001', 'Dupont', 'Jean', 'Senior'],
            ['LIC-002', 'Martin', 'Claire', 'Junior'],
            ['LIC-003', 'Bernard', 'Luc', 'Cadet'],
        ]);

        $batch = $this->runImportSuccessfully();

        $this->assertBatchCounts($batch, rawRowsCount: 3, validRowsCount: 3, invalidRowsCount: 0);
        $this->assertBatchActivated($batch);
        $this->assertOnlyOneActiveBatch();
    }

    public function test_it_fails_comparative_validation_when_valid_rows_variation_reaches_reject_threshold(): void
    {
        $this->createActiveBatchWithSummary(
            rawRowsCount: 4,
            validRowsCount: 4,
            invalidRowsCount: 0,
            requiredFieldFillRates: [
                'license_number' => 100,
                'last_name' => 100,
                'first_name' => 100,
            ],
        );

        config()->set('license_import.validation.minimal.required_field_fill_rate_percent', [
            'license_number' => 0,
            'last_name' => 100,
            'first_name' => 100,
        ]);
        config()->set('license_import.validation.minimal.minimum_valid_ratio_percent', 50);
        config()->set('license_import.validation.minimal.maximum_invalid_ratio_percent', 50);

        $this->enableComparativeValidation(
            validRowsVariation: ['warning_percent' => 10, 'reject_percent' => 50],
        );

        $this->fakeHtmlTable([
            ['LIC-001', 'Dupont', 'Jean', 'Senior'],
            ['LIC-002', 'Martin', 'Claire', 'Junior'],
            ['', 'Bernard', 'Luc', 'Cadet'],
            ['', 'Petit', 'Anne', 'Minime'],
        ]);

        $batch = $this->runImportExpectingRuntimeException(
            'Comparative validation failed for the current import batch.',
        );

        $this->assertBatchCounts($batch, rawRowsCount: 4, validRowsCount: 2, invalidRowsCount: 2);
        $this->assertBatchIsFailed($batch);
        $this->assertSnapshotValidity($batch, [true, true, false, false]);
        $this->assertBatchHasIssueCode($batch, 'comparative_valid_rows_variation_reject');
    }

    public function test_it_fails_comparative_validation_when_invalid_ratio_variation_reaches_reject_threshold(): void
    {
        $this->createActiveBatchWithSummary(
            rawRowsCount: 4,
            validRowsCount: 4,
            invalidRowsCount: 0,
            requiredFieldFillRates: [
                'license_number' => 100,
                'last_name' => 100,
                'first_name' => 100,
            ],
        );

        config()->set('license_import.validation.minimal.required_field_fill_rate_percent', [
            'license_number' => 0,
            'last_name' => 100,
            'first_name' => 100,
        ]);
        config()->set('license_import.validation.minimal.minimum_valid_ratio_percent', 50);
        config()->set('license_import.validation.minimal.maximum_invalid_ratio_percent', 50);

        $this->enableComparativeValidation(
            invalidRatioVariation: ['warning_percent' => 10, 'reject_percent' => 50],
        );

        $this->fakeHtmlTable([
            ['LIC-001', 'Dupont', 'Jean', 'Senior'],
            ['LIC-002', 'Martin', 'Claire', 'Junior'],
            ['', 'Bernard', 'Luc', 'Cadet'],
            ['', 'Petit', 'Anne', 'Minime'],
        ]);

        $batch = $this->runImportExpectingRuntimeException(
            'Comparative validation failed for the current import batch.',
        );

        $this->assertBatchCounts($batch, rawRowsCount: 4, validRowsCount: 2, invalidRowsCount: 2);
        $this->assertBatchIsFailed($batch);
        $this->assertBatchHasIssueCode($batch, 'comparative_invalid_ratio_variation_reject');
    }

    public function test_it_fails_comparative_validation_when_required_field_fill_rate_variation_reaches_reject_threshold(): void
    {
        $this->createActiveBatchWithSummary(
            rawRowsCount: 4,
            validRowsCount: 4,
            invalidRowsCount: 0,
            requiredFieldFillRates: [
                'license_number' => 100,
                'last_name' => 100,
                'first_name' => 100,
            ],
        );

        config()->set('license_import.validation.minimal.required_field_fill_rate_percent', [
            'license_number' => 50,
            'last_name' => 100,
            'first_name' => 100,
        ]);
        config()->set('license_import.validation.minimal.minimum_valid_ratio_percent', 50);
        config()->set('license_import.validation.minimal.maximum_invalid_ratio_percent', 50);

        $this->enableComparativeValidation(
            fieldFillRateVariation: ['warning_percent' => 10, 'reject_percent' => 50],
        );

        $this->fakeHtmlTable([
            ['LIC-001', 'Dupont', 'Jean', 'Senior'],
            ['LIC-002', 'Martin', 'Claire', 'Junior'],
            ['', 'Bernard', 'Luc', 'Cadet'],
            ['', 'Petit', 'Anne', 'Minime'],
        ]);

        $batch = $this->runImportExpectingRuntimeException(
            'Comparative validation failed for the current import batch.',
        );

        $this->assertBatchCounts($batch, rawRowsCount: 4, validRowsCount: 2, invalidRowsCount: 2);
        $this->assertBatchIsFailed($batch);
        $this->assertBatchHasIssueCode($batch, 'comparative_field_fill_rate_variation_license_number_reject');
    }

    public function test_it_marks_batch_as_succeeded_without_activation_when_running_in_dry_run(): void
    {
        $this->fakeHtmlTable([
            ['LIC-001', 'Dupont', 'Jean', 'Senior'],
            ['LIC-002', 'Martin', 'Claire', 'Junior'],
        ]);

        $context = $this->makeExecutionContext(dryRun: true);

        /** @var TelematLicenseImportOrchestrator $orchestrator */
        $orchestrator = app(TelematLicenseImportOrchestrator::class);

        $orchestrator->run($context);

        $batch = $this->latestBatch();

        $this->assertNotNull($batch);

        $batch->refresh();

        $this->assertNotSame(BatchStatus::Failed, $batch->status);
        $this->assertFalse($batch->is_active);
        $this->assertNull($batch->activated_at);
        $this->assertNotNull($batch->started_at);
        $this->assertNotNull($batch->finished_at);
        $this->assertTrue($batch->isTerminal());

        $this->assertSame(2, $batch->raw_rows_count);
        $this->assertSame(2, $batch->valid_rows_count);
        $this->assertSame(0, $batch->invalid_rows_count);
        $this->assertSame(2, $batch->snapshots()->count());

        $meta = is_array($batch->meta) ? $batch->meta : [];
        $executionMeta = is_array($meta['execution'] ?? null) ? $meta['execution'] : [];
        $activationMeta = is_array($meta['activation'] ?? null) ? $meta['activation'] : [];

        $this->assertTrue($executionMeta['finished'] ?? false);
        $this->assertSame('succeeded', $executionMeta['result'] ?? null);
        $this->assertSame('dry_run', $executionMeta['final_outcome'] ?? null);
        $this->assertNotNull($executionMeta['finished_at'] ?? null);

        $this->assertFalse($activationMeta['attempted'] ?? true);
        $this->assertFalse($activationMeta['activated'] ?? true);
        $this->assertSame('dry_run', $activationMeta['reason'] ?? null);
        $this->assertTrue($activationMeta['effective_dry_run'] ?? false);

        $summary = is_array($batch->summary) ? $batch->summary : [];
        $executionSummary = is_array($summary['execution'] ?? null) ? $summary['execution'] : [];
        $activationSummary = is_array($summary['activation'] ?? null) ? $summary['activation'] : [];

        $this->assertTrue($executionSummary['finished'] ?? false);
        $this->assertSame('succeeded', $executionSummary['result'] ?? null);
        $this->assertSame('dry_run', $executionSummary['final_outcome'] ?? null);

        $this->assertFalse($activationSummary['attempted'] ?? true);
        $this->assertFalse($activationSummary['activated'] ?? true);
        $this->assertSame('dry_run', $activationSummary['reason'] ?? null);

        $this->assertSame(0, $batch->issues()->count());
        $this->assertSame(0, LicenseImportBatch::query()->where('source', 'ffbi_telemat')->where('is_active', true)->count());
    }

    public function test_it_marks_batch_as_succeeded_without_activation_when_auto_activation_is_disabled(): void
    {
        config()->set('license_import.activation.auto_activate_when_valid', false);

        $this->fakeHtmlTable([
            ['LIC-001', 'Dupont', 'Jean', 'Senior'],
            ['LIC-002', 'Martin', 'Claire', 'Junior'],
        ]);

        $batch = $this->runImportSuccessfully();

        $batch->refresh();

        $this->assertNotSame(BatchStatus::Failed, $batch->status);
        $this->assertFalse($batch->is_active);
        $this->assertNull($batch->activated_at);
        $this->assertNotNull($batch->started_at);
        $this->assertNotNull($batch->finished_at);
        $this->assertTrue($batch->isTerminal());

        $this->assertSame(2, $batch->raw_rows_count);
        $this->assertSame(2, $batch->valid_rows_count);
        $this->assertSame(0, $batch->invalid_rows_count);
        $this->assertSame(2, $batch->snapshots()->count());

        $meta = is_array($batch->meta) ? $batch->meta : [];
        $executionMeta = is_array($meta['execution'] ?? null) ? $meta['execution'] : [];
        $activationMeta = is_array($meta['activation'] ?? null) ? $meta['activation'] : [];

        $this->assertTrue($executionMeta['finished'] ?? false);
        $this->assertSame('succeeded', $executionMeta['result'] ?? null);
        $this->assertSame('auto_activation_disabled', $executionMeta['final_outcome'] ?? null);
        $this->assertNotNull($executionMeta['finished_at'] ?? null);

        $this->assertFalse($activationMeta['attempted'] ?? true);
        $this->assertFalse($activationMeta['activated'] ?? true);
        $this->assertSame('auto_activation_disabled', $activationMeta['reason'] ?? null);
        $this->assertFalse($activationMeta['effective_dry_run'] ?? true);

        $summary = is_array($batch->summary) ? $batch->summary : [];
        $executionSummary = is_array($summary['execution'] ?? null) ? $summary['execution'] : [];
        $activationSummary = is_array($summary['activation'] ?? null) ? $summary['activation'] : [];

        $this->assertTrue($executionSummary['finished'] ?? false);
        $this->assertSame('succeeded', $executionSummary['result'] ?? null);
        $this->assertSame('auto_activation_disabled', $executionSummary['final_outcome'] ?? null);

        $this->assertFalse($activationSummary['attempted'] ?? true);
        $this->assertFalse($activationSummary['activated'] ?? true);
        $this->assertSame('auto_activation_disabled', $activationSummary['reason'] ?? null);

        $this->assertSame(0, $batch->issues()->count());
        $this->assertSame(0, LicenseImportBatch::query()->where('source', 'ffbi_telemat')->where('is_active', true)->count());
    }

    public function test_it_parses_and_activates_a_realistic_telemat_html_snapshot(): void
    {
        $this->fakeSuccessfulHtmlResponse(
            $this->telematRealClubHtmlSnapshot()
        );

        $batch = $this->runImportSuccessfully();

        $this->assertBatchActivated($batch);
        $this->assertBatchCounts($batch, rawRowsCount: 5, validRowsCount: 5, invalidRowsCount: 0);

        $snapshots = $batch->snapshots()->orderBy('row_index')->get();

        $this->assertCount(5, $snapshots);

        $this->assertSame('191100 S', $snapshots[0]->license_number);
        $this->assertSame('ARROUAS', $snapshots[0]->last_name);
        $this->assertSame('ISABELLE', $snapshots[0]->first_name);
        $this->assertSame('Decouverte', $snapshots[0]->category);

        $this->assertSame('018952 Y', $snapshots[2]->license_number);
        $this->assertSame('AUGER', $snapshots[2]->last_name);
        $this->assertSame('WILLIAM', $snapshots[2]->first_name);
        $this->assertSame('+21 ans', $snapshots[2]->category);

        $this->assertSame('137219 R', $snapshots[4]->license_number);
        $this->assertSame('BOUTEILLE', $snapshots[4]->last_name);
        $this->assertSame('MATHEO', $snapshots[4]->first_name);
        $this->assertSame('-21 ans', $snapshots[4]->category);

        $this->assertSame(
            ['Numéro', 'Nom', 'Prénom', 'Catégorie'],
            $batch->source_columns,
        );

        $this->assertSame(0, $batch->issues()->count());
    }

    public function test_it_selects_the_right_table_with_header_detection_fallback_when_configured_selector_does_not_match(): void
    {
        config()->set('license_import.parsing.table_selector', '#does-not-exist');
        config()->set('license_import.parsing.use_header_detection_fallback', true);

        $this->fakeSuccessfulHtmlResponse(
            $this->telematRealisticHtmlWithoutConfiguredSelectorButWithFallbackCandidate()
        );

        $batch = $this->runImportSuccessfully();

        $this->assertBatchActivated($batch);
        $this->assertBatchCounts($batch, rawRowsCount: 2, validRowsCount: 2, invalidRowsCount: 0);

        $snapshots = $batch->snapshots()->orderBy('row_index')->get();

        $this->assertCount(2, $snapshots);

        $this->assertSame('191100 S', $snapshots[0]->license_number);
        $this->assertSame('ARROUAS', $snapshots[0]->last_name);
        $this->assertSame('ISABELLE', $snapshots[0]->first_name);
        $this->assertSame('Decouverte', $snapshots[0]->category);

        $this->assertSame('190399 F', $snapshots[1]->license_number);
        $this->assertSame('AUCHART', $snapshots[1]->last_name);
        $this->assertSame('THIERRY', $snapshots[1]->first_name);
        $this->assertSame('Decouverte', $snapshots[1]->category);

        $this->assertSame(
            ['Numéro', 'Nom', 'Prénom', 'Catégorie'],
            $batch->source_columns,
        );

        $this->assertSame(0, $batch->issues()->count());
    }

    public function test_it_parses_a_realistic_html_snapshot_with_noisy_but_normalizable_headers(): void
    {
        $this->fakeSuccessfulHtmlResponse(
            $this->telematHtmlWithNoisyButNormalizableHeaders()
        );

        $batch = $this->runImportSuccessfully();

        $this->assertBatchActivated($batch);
        $this->assertBatchCounts($batch, rawRowsCount: 2, validRowsCount: 2, invalidRowsCount: 0);

        $snapshots = $batch->snapshots()->orderBy('row_index')->get();

        $this->assertCount(2, $snapshots);

        $this->assertSame('191100 S', $snapshots[0]->license_number);
        $this->assertSame('ARROUAS', $snapshots[0]->last_name);
        $this->assertSame('ISABELLE', $snapshots[0]->first_name);
        $this->assertSame('Decouverte', $snapshots[0]->category);

        $this->assertSame('190399 F', $snapshots[1]->license_number);
        $this->assertSame('AUCHART', $snapshots[1]->last_name);
        $this->assertSame('THIERRY', $snapshots[1]->first_name);
        $this->assertSame('Decouverte', $snapshots[1]->category);

        $this->assertSame(0, $batch->issues()->count());
    }

    public function test_it_fails_cleanly_when_a_realistic_table_is_found_but_required_mapping_is_no_longer_possible(): void
    {
        $this->fakeSuccessfulHtmlResponse(
            $this->telematHtmlWithHeadersThatDoNotMatchRequiredFieldMapping()
        );

        $batch = $this->runImportExpectingRuntimeException(
            'Minimal validation failed for the current import batch.',
        );

        $this->assertBatchIsFailed($batch);
        $this->assertBatchCounts($batch, rawRowsCount: 2, validRowsCount: 0, invalidRowsCount: 2);

        $snapshots = $batch->snapshots()->orderBy('row_index')->get();

        $this->assertCount(2, $snapshots);

        $this->assertNull($snapshots[0]->license_number);
        $this->assertNull($snapshots[0]->last_name);
        $this->assertNull($snapshots[0]->first_name);

        $this->assertNull($snapshots[1]->license_number);
        $this->assertNull($snapshots[1]->last_name);
        $this->assertNull($snapshots[1]->first_name);

        $this->assertBatchHasIssueCode($batch, 'minimum_valid_ratio_not_reached');
        $this->assertBatchHasIssueCode($batch, 'maximum_invalid_ratio_exceeded');
        $this->assertBatchHasIssueCode($batch, 'required_field_fill_rate_below_threshold');

        $this->assertSame(
            ['Licence', 'Nom complet', 'Prénom complet', 'Classe'],
            $batch->source_columns,
        );
    }

    public function test_it_deactivates_previous_active_batch_when_a_new_valid_batch_is_activated(): void
    {
        $previousBatch = $this->createActiveBatchWithSummary(
            rawRowsCount: 2,
            validRowsCount: 2,
            invalidRowsCount: 0,
            requiredFieldFillRates: [
                'license_number' => 100,
                'last_name' => 100,
                'first_name' => 100,
            ],
        );

        $this->enableComparativeValidation(
            totalRowsVariation: ['warning_percent' => 100, 'reject_percent' => 100],
            validRowsVariation: ['warning_percent' => 100, 'reject_percent' => 100],
            invalidRatioVariation: ['warning_percent' => 100, 'reject_percent' => 100],
            fieldFillRateVariation: ['warning_percent' => 100, 'reject_percent' => 100],
        );

        $this->fakeHtmlTable([
            ['LIC-003', 'Bernard', 'Luc', 'Cadet'],
            ['LIC-004', 'Petit', 'Anne', 'Minime'],
        ]);

        $batch = $this->runImportSuccessfully();

        $this->assertBatchCounts($batch, rawRowsCount: 2, validRowsCount: 2, invalidRowsCount: 0);
        $this->assertBatchActivated($batch);
        $this->assertOnlyOneActiveBatch();

        $previousBatch->refresh();
        $batch->refresh();

        $this->assertFalse($previousBatch->is_active);
        $this->assertTrue($batch->is_active);
        $this->assertSame($previousBatch->id, data_get($batch->summary, 'comparative_validation.previous_batch_id'));
        $this->assertFalse((bool) data_get($batch->summary, 'comparative_validation.skipped', true));
        $this->assertSame('activated', data_get($batch->summary, 'execution.final_outcome'));
        $this->assertSame('auto_activate_when_valid', data_get($batch->summary, 'activation.reason'));
    }

    public function test_it_keeps_previous_active_batch_when_second_import_runs_in_dry_run(): void
    {
        $previousBatch = $this->createActiveBatchWithSummary(
            rawRowsCount: 2,
            validRowsCount: 2,
            invalidRowsCount: 0,
            requiredFieldFillRates: [
                'license_number' => 100,
                'last_name' => 100,
                'first_name' => 100,
            ],
        );

        $this->enableComparativeValidation(
            totalRowsVariation: ['warning_percent' => 100, 'reject_percent' => 100],
            validRowsVariation: ['warning_percent' => 100, 'reject_percent' => 100],
            invalidRatioVariation: ['warning_percent' => 100, 'reject_percent' => 100],
            fieldFillRateVariation: ['warning_percent' => 100, 'reject_percent' => 100],
        );

        $this->fakeHtmlTable([
            ['LIC-003', 'Bernard', 'Luc', 'Cadet'],
            ['LIC-004', 'Petit', 'Anne', 'Minime'],
        ]);

        $context = $this->makeExecutionContext(dryRun: true);

        /** @var TelematLicenseImportOrchestrator $orchestrator */
        $orchestrator = app(TelematLicenseImportOrchestrator::class);

        $orchestrator->run($context);

        $batch = $this->latestBatch();

        $this->assertNotNull($batch);

        $batch->refresh();
        $previousBatch->refresh();

        $this->assertSame(2, $batch->raw_rows_count);
        $this->assertSame(2, $batch->valid_rows_count);
        $this->assertSame(0, $batch->invalid_rows_count);

        $this->assertFalse($batch->is_active);
        $this->assertNull($batch->activated_at);
        $this->assertNotNull($batch->finished_at);

        $this->assertTrue($previousBatch->is_active);
        $this->assertNotNull($previousBatch->activated_at);

        $this->assertOnlyOneActiveBatch();

        $this->assertSame($previousBatch->id, data_get($batch->summary, 'comparative_validation.previous_batch_id'));
        $this->assertFalse((bool) data_get($batch->summary, 'comparative_validation.skipped', true));
        $this->assertSame('dry_run', data_get($batch->summary, 'execution.final_outcome'));
        $this->assertSame('dry_run', data_get($batch->summary, 'activation.reason'));
        $this->assertFalse((bool) data_get($batch->summary, 'activation.activated', true));
    }

    public function test_it_keeps_previous_active_batch_when_second_import_is_rejected_by_comparative_validation(): void
    {
        $previousBatch = $this->createActiveBatchWithSummary(
            rawRowsCount: 4,
            validRowsCount: 4,
            invalidRowsCount: 0,
            requiredFieldFillRates: [
                'license_number' => 100,
                'last_name' => 100,
                'first_name' => 100,
            ],
        );

        $this->enableComparativeValidation(
            totalRowsVariation: ['warning_percent' => 10, 'reject_percent' => 50],
        );

        $this->fakeHtmlTable([
            ['LIC-001', 'Dupont', 'Jean', 'Senior'],
            ['LIC-002', 'Martin', 'Claire', 'Junior'],
        ]);

        $batch = $this->runImportExpectingRuntimeException(
            'Comparative validation failed for the current import batch.',
        );

        $batch->refresh();
        $previousBatch->refresh();

        $this->assertBatchCounts($batch, rawRowsCount: 2, validRowsCount: 2, invalidRowsCount: 0);
        $this->assertBatchIsFailed($batch);
        $this->assertBatchHasIssueCode($batch, 'comparative_total_rows_variation_reject');

        $this->assertFalse($batch->is_active);
        $this->assertNull($batch->activated_at);

        $this->assertTrue($previousBatch->is_active);
        $this->assertNotNull($previousBatch->activated_at);

        $this->assertOnlyOneActiveBatch();

        $this->assertSame($previousBatch->id, data_get($batch->summary, 'comparative_validation.previous_batch_id'));
        $this->assertFalse((bool) data_get($batch->summary, 'comparative_validation.skipped', true));
        $this->assertSame('failed', data_get($batch->summary, 'execution.final_outcome'));
    }

    public function test_it_stores_previous_batch_reference_when_comparative_validation_runs_against_an_active_batch(): void
    {
        $previousBatch = $this->createActiveBatchWithSummary(
            rawRowsCount: 2,
            validRowsCount: 2,
            invalidRowsCount: 0,
            requiredFieldFillRates: [
                'license_number' => 100,
                'last_name' => 100,
                'first_name' => 100,
            ],
        );

        $this->enableComparativeValidation(
            totalRowsVariation: ['warning_percent' => 100, 'reject_percent' => 100],
            validRowsVariation: ['warning_percent' => 100, 'reject_percent' => 100],
            invalidRatioVariation: ['warning_percent' => 100, 'reject_percent' => 100],
            fieldFillRateVariation: ['warning_percent' => 100, 'reject_percent' => 100],
        );

        $this->fakeHtmlTable([
            ['LIC-003', 'Bernard', 'Luc', 'Cadet'],
            ['LIC-004', 'Petit', 'Anne', 'Minime'],
        ]);

        $batch = $this->runImportSuccessfully();

        $this->assertBatchActivated($batch);

        $comparative = is_array($batch->summary['comparative_validation'] ?? null)
            ? $batch->summary['comparative_validation']
            : [];

        $this->assertTrue((bool) ($comparative['enabled'] ?? false));
        $this->assertFalse((bool) ($comparative['skipped'] ?? true));
        $this->assertSame($previousBatch->id, $comparative['previous_batch_id'] ?? null);
        $this->assertSame(2, $comparative['current']['raw_rows_count'] ?? null);
        $this->assertSame(2, $comparative['current']['valid_rows_count'] ?? null);
        $this->assertSame(0, $comparative['current']['invalid_rows_count'] ?? null);
        $this->assertSame(2, $comparative['previous']['raw_rows_count'] ?? null);
        $this->assertSame(2, $comparative['previous']['valid_rows_count'] ?? null);
        $this->assertSame(0, $comparative['previous']['invalid_rows_count'] ?? null);
    }

    public function test_it_activates_a_second_batch_when_the_imported_dataset_is_identical_to_the_previous_active_batch(): void
    {
        $previousBatch = $this->createActiveBatchWithSummary(
            rawRowsCount: 2,
            validRowsCount: 2,
            invalidRowsCount: 0,
            requiredFieldFillRates: [
                'license_number' => 100,
                'last_name' => 100,
                'first_name' => 100,
            ],
        );

        $this->enableComparativeValidation(
            totalRowsVariation: ['warning_percent' => 100, 'reject_percent' => 100],
            validRowsVariation: ['warning_percent' => 100, 'reject_percent' => 100],
            invalidRatioVariation: ['warning_percent' => 100, 'reject_percent' => 100],
            fieldFillRateVariation: ['warning_percent' => 100, 'reject_percent' => 100],
        );

        $this->fakeHtmlTable([
            ['LIC-001', 'Dupont', 'Jean', 'Senior'],
            ['LIC-002', 'Martin', 'Claire', 'Junior'],
        ]);

        $batch = $this->runImportSuccessfully();

        $batch->refresh();
        $previousBatch->refresh();

        $this->assertBatchCounts($batch, rawRowsCount: 2, validRowsCount: 2, invalidRowsCount: 0);
        $this->assertBatchActivated($batch);

        $this->assertFalse($previousBatch->is_active);
        $this->assertTrue($batch->is_active);
        $this->assertOnlyOneActiveBatch();

        $this->assertSame(2, $batch->snapshots()->count());
        $this->assertSame($previousBatch->id, data_get($batch->summary, 'comparative_validation.previous_batch_id'));
        $this->assertFalse((bool) data_get($batch->summary, 'comparative_validation.skipped', true));
    }

    public function test_it_computes_identical_comparative_metrics_when_the_second_import_matches_the_previous_active_batch(): void
    {
        $previousBatch = $this->createActiveBatchWithSummary(
            rawRowsCount: 2,
            validRowsCount: 2,
            invalidRowsCount: 0,
            requiredFieldFillRates: [
                'license_number' => 100,
                'last_name' => 100,
                'first_name' => 100,
            ],
        );

        $this->enableComparativeValidation(
            totalRowsVariation: ['warning_percent' => 100, 'reject_percent' => 100],
            validRowsVariation: ['warning_percent' => 100, 'reject_percent' => 100],
            invalidRatioVariation: ['warning_percent' => 100, 'reject_percent' => 100],
            fieldFillRateVariation: ['warning_percent' => 100, 'reject_percent' => 100],
        );

        $this->fakeHtmlTable([
            ['LIC-001', 'Dupont', 'Jean', 'Senior'],
            ['LIC-002', 'Martin', 'Claire', 'Junior'],
        ]);

        $batch = $this->runImportSuccessfully();

        $comparative = is_array($batch->summary['comparative_validation'] ?? null)
            ? $batch->summary['comparative_validation']
            : [];

        $this->assertTrue((bool) ($comparative['enabled'] ?? false));
        $this->assertFalse((bool) ($comparative['skipped'] ?? true));
        $this->assertSame($previousBatch->id, $comparative['previous_batch_id'] ?? null);

        $this->assertSame(2, $comparative['current']['raw_rows_count'] ?? null);
        $this->assertSame(2, $comparative['current']['valid_rows_count'] ?? null);
        $this->assertSame(0, $comparative['current']['invalid_rows_count'] ?? null);
        $this->assertSame(0, $comparative['current']['invalid_ratio_percent'] ?? null);
        $this->assertSame([
            'license_number' => 100,
            'last_name' => 100,
            'first_name' => 100,
        ], $comparative['current']['required_field_fill_rates'] ?? null);

        $this->assertSame(2, $comparative['previous']['raw_rows_count'] ?? null);
        $this->assertSame(2, $comparative['previous']['valid_rows_count'] ?? null);
        $this->assertSame(0, $comparative['previous']['invalid_rows_count'] ?? null);
        $this->assertSame(0, $comparative['previous']['invalid_ratio_percent'] ?? null);
        $this->assertSame([
            'license_number' => 100,
            'last_name' => 100,
            'first_name' => 100,
        ], $comparative['previous']['required_field_fill_rates'] ?? null);
    }

    public function test_it_keeps_the_same_source_fingerprint_when_the_second_import_contains_the_exact_same_rows(): void
    {
        $previousBatch = $this->createActiveBatchWithSummary(
            rawRowsCount: 2,
            validRowsCount: 2,
            invalidRowsCount: 0,
            requiredFieldFillRates: [
                'license_number' => 100,
                'last_name' => 100,
                'first_name' => 100,
            ],
        );

        $previousBatch->forceFill([
            'source_fingerprint' => null,
        ])->save();

        $this->enableComparativeValidation(
            totalRowsVariation: ['warning_percent' => 100, 'reject_percent' => 100],
            validRowsVariation: ['warning_percent' => 100, 'reject_percent' => 100],
            invalidRatioVariation: ['warning_percent' => 100, 'reject_percent' => 100],
            fieldFillRateVariation: ['warning_percent' => 100, 'reject_percent' => 100],
        );

        $this->fakeHtmlTable([
            ['LIC-001', 'Dupont', 'Jean', 'Senior'],
            ['LIC-002', 'Martin', 'Claire', 'Junior'],
        ]);

        $firstImportedBatch = $this->runImportSuccessfully();

        $firstFingerprint = $firstImportedBatch->source_fingerprint;

        $this->assertIsString($firstFingerprint);
        $this->assertNotSame('', $firstFingerprint);

        $this->fakeHtmlTable([
            ['LIC-001', 'Dupont', 'Jean', 'Senior'],
            ['LIC-002', 'Martin', 'Claire', 'Junior'],
        ]);

        $secondBatch = $this->runImportSuccessfully();

        $secondBatch->refresh();

        $this->assertSame($firstFingerprint, $secondBatch->source_fingerprint);
    }

    public function test_it_does_not_create_comparative_issues_when_the_second_import_is_identical_to_the_previous_active_batch(): void
    {
        $this->createActiveBatchWithSummary(
            rawRowsCount: 2,
            validRowsCount: 2,
            invalidRowsCount: 0,
            requiredFieldFillRates: [
                'license_number' => 100,
                'last_name' => 100,
                'first_name' => 100,
            ],
        );

        $this->enableComparativeValidation(
            totalRowsVariation: ['warning_percent' => 10, 'reject_percent' => 20],
            validRowsVariation: ['warning_percent' => 10, 'reject_percent' => 20],
            invalidRatioVariation: ['warning_percent' => 10, 'reject_percent' => 20],
            fieldFillRateVariation: ['warning_percent' => 10, 'reject_percent' => 20],
        );

        $this->fakeHtmlTable([
            ['LIC-001', 'Dupont', 'Jean', 'Senior'],
            ['LIC-002', 'Martin', 'Claire', 'Junior'],
        ]);

        $batch = $this->runImportSuccessfully();

        $batch->refresh();

        $this->assertBatchActivated($batch);
        $this->assertSame(0, $batch->issues()->count());
        $this->assertSame(0, $batch->error_count);
        $this->assertSame(0, $batch->warning_count);
    }

    public function test_it_activates_the_second_batch_when_total_rows_variation_only_reaches_warning_threshold(): void
    {
        $previousBatch = $this->createActiveBatchWithSummary(
            rawRowsCount: 10,
            validRowsCount: 10,
            invalidRowsCount: 0,
            requiredFieldFillRates: [
                'license_number' => 100,
                'last_name' => 100,
                'first_name' => 100,
            ],
        );

        $this->enableComparativeValidation(
            totalRowsVariation: ['warning_percent' => 10, 'reject_percent' => 50],
            validRowsVariation: ['warning_percent' => 100, 'reject_percent' => 100],
            invalidRatioVariation: ['warning_percent' => 100, 'reject_percent' => 100],
            fieldFillRateVariation: ['warning_percent' => 100, 'reject_percent' => 100],
        );

        $this->fakeHtmlTable([
            ['LIC-001', 'Dupont', 'Jean', 'Senior'],
            ['LIC-002', 'Martin', 'Claire', 'Junior'],
            ['LIC-003', 'Bernard', 'Luc', 'Cadet'],
            ['LIC-004', 'Petit', 'Anne', 'Minime'],
            ['LIC-005', 'Durand', 'Paul', 'Senior'],
            ['LIC-006', 'Moreau', 'Eva', 'Junior'],
            ['LIC-007', 'Simon', 'Hugo', 'Cadet'],
            ['LIC-008', 'Laurent', 'Nina', 'Minime'],
            ['LIC-009', 'Michel', 'Leo', 'Senior'],
        ]);

        $batch = $this->runImportSuccessfully();

        $batch->refresh();
        $previousBatch->refresh();

        $this->assertBatchCounts($batch, rawRowsCount: 9, validRowsCount: 9, invalidRowsCount: 0);
        $this->assertBatchActivated($batch);

        $this->assertFalse($previousBatch->is_active);
        $this->assertTrue($batch->is_active);
        $this->assertOnlyOneActiveBatch();

        $this->assertBatchHasIssueCode($batch, 'comparative_total_rows_variation_warning');
        $this->assertSame(0, $batch->error_count);
        $this->assertSame(1, $batch->warning_count);
    }

    public function test_it_activates_the_second_batch_when_valid_rows_variation_only_reaches_warning_threshold(): void
    {
        $this->createActiveBatchWithSummary(
            rawRowsCount: 10,
            validRowsCount: 10,
            invalidRowsCount: 0,
            requiredFieldFillRates: [
                'license_number' => 100,
                'last_name' => 100,
                'first_name' => 100,
            ],
        );

        config()->set('license_import.validation.minimal.required_field_fill_rate_percent', [
            'license_number' => 0,
            'last_name' => 100,
            'first_name' => 100,
        ]);
        config()->set('license_import.validation.minimal.minimum_valid_ratio_percent', 50);
        config()->set('license_import.validation.minimal.maximum_invalid_ratio_percent', 50);

        $this->enableComparativeValidation(
            totalRowsVariation: ['warning_percent' => 101, 'reject_percent' => 101],
            validRowsVariation: ['warning_percent' => 20, 'reject_percent' => 90],
            invalidRatioVariation: ['warning_percent' => 101, 'reject_percent' => 101],
            fieldFillRateVariation: ['warning_percent' => 101, 'reject_percent' => 101],
        );

        $this->fakeHtmlTable([
            ['LIC-001', 'Dupont', 'Jean', 'Senior'],
            ['LIC-002', 'Martin', 'Claire', 'Junior'],
            ['LIC-003', 'Bernard', 'Luc', 'Cadet'],
            ['LIC-004', 'Petit', 'Anne', 'Minime'],
            ['LIC-005', 'Moreau', 'Paul', 'Senior'],
            ['LIC-006', 'Simon', 'Lea', 'Junior'],
            ['LIC-007', 'Roux', 'Marc', 'Cadet'],
            ['LIC-008', 'Lefevre', 'Julie', 'Minime'],
            ['', 'Robert', 'Nina', 'Senior'],
            ['', 'Garcia', 'Hugo', 'Junior'],
        ]);

        $batch = $this->runImportSuccessfully();

        $this->assertBatchCounts($batch, rawRowsCount: 10, validRowsCount: 8, invalidRowsCount: 2);
        $this->assertBatchActivated($batch);
        $this->assertBatchHasIssueCode($batch, 'comparative_valid_rows_variation_warning');
        $this->assertOnlyOneActiveBatch();
    }

    public function test_it_activates_the_second_batch_when_invalid_ratio_variation_only_reaches_warning_threshold(): void
    {
        $this->createActiveBatchWithSummary(
            rawRowsCount: 10,
            validRowsCount: 10,
            invalidRowsCount: 0,
            requiredFieldFillRates: [
                'license_number' => 100,
                'last_name' => 100,
                'first_name' => 100,
            ],
        );

        config()->set('license_import.validation.minimal.required_field_fill_rate_percent', [
            'license_number' => 0,
            'last_name' => 100,
            'first_name' => 100,
        ]);
        config()->set('license_import.validation.minimal.minimum_valid_ratio_percent', 80);
        config()->set('license_import.validation.minimal.maximum_invalid_ratio_percent', 20);

        $this->enableComparativeValidation(
            totalRowsVariation: ['warning_percent' => 101, 'reject_percent' => 101],
            validRowsVariation: ['warning_percent' => 101, 'reject_percent' => 101],
            invalidRatioVariation: ['warning_percent' => 20, 'reject_percent' => 101],
            fieldFillRateVariation: ['warning_percent' => 101, 'reject_percent' => 101],
        );

        $this->fakeHtmlTable([
            ['LIC-001', 'Dupont', 'Jean', 'Senior'],
            ['LIC-002', 'Martin', 'Claire', 'Junior'],
            ['LIC-003', 'Bernard', 'Luc', 'Cadet'],
            ['LIC-004', 'Petit', 'Anne', 'Minime'],
            ['LIC-005', 'Moreau', 'Paul', 'Senior'],
            ['LIC-006', 'Simon', 'Lea', 'Junior'],
            ['LIC-007', 'Roux', 'Marc', 'Cadet'],
            ['LIC-008', 'Lefevre', 'Julie', 'Minime'],
            ['', 'Robert', 'Nina', 'Senior'],
            ['', 'Garcia', 'Hugo', 'Junior'],
        ]);

        $batch = $this->runImportSuccessfully();

        $this->assertBatchCounts($batch, rawRowsCount: 10, validRowsCount: 8, invalidRowsCount: 2);
        $this->assertBatchActivated($batch);
        $this->assertBatchHasIssueCode($batch, 'comparative_invalid_ratio_variation_warning');
        $this->assertOnlyOneActiveBatch();
    }

    public function test_it_activates_the_second_batch_when_required_field_fill_rate_variation_only_reaches_warning_threshold(): void
    {
        $this->createActiveBatchWithSummary(
            rawRowsCount: 10,
            validRowsCount: 10,
            invalidRowsCount: 0,
            requiredFieldFillRates: [
                'license_number' => 100,
                'last_name' => 100,
                'first_name' => 100,
            ],
        );

        config()->set('license_import.validation.minimal.required_field_fill_rate_percent', [
            'license_number' => 80,
            'last_name' => 100,
            'first_name' => 100,
        ]);
        config()->set('license_import.validation.minimal.minimum_valid_ratio_percent', 0);
        config()->set('license_import.validation.minimal.maximum_invalid_ratio_percent', 100);

        $this->enableComparativeValidation(
            totalRowsVariation: ['warning_percent' => 101, 'reject_percent' => 101],
            validRowsVariation: ['warning_percent' => 101, 'reject_percent' => 101],
            invalidRatioVariation: ['warning_percent' => 101, 'reject_percent' => 101],
            fieldFillRateVariation: ['warning_percent' => 20, 'reject_percent' => 90],
        );

        $this->fakeHtmlTable([
            ['LIC-001', 'Dupont', 'Jean', 'Senior'],
            ['LIC-002', 'Martin', 'Claire', 'Junior'],
            ['LIC-003', 'Bernard', 'Luc', 'Cadet'],
            ['LIC-004', 'Petit', 'Anne', 'Minime'],
            ['LIC-005', 'Moreau', 'Paul', 'Senior'],
            ['LIC-006', 'Simon', 'Lea', 'Junior'],
            ['LIC-007', 'Roux', 'Marc', 'Cadet'],
            ['LIC-008', 'Lefevre', 'Julie', 'Minime'],
            ['', 'Robert', 'Nina', 'Senior'],
            ['', 'Garcia', 'Hugo', 'Junior'],
        ]);

        $batch = $this->runImportSuccessfully();

        $this->assertBatchCounts($batch, rawRowsCount: 10, validRowsCount: 8, invalidRowsCount: 2);
        $this->assertBatchActivated($batch);
        $this->assertBatchHasIssueCode($batch, 'comparative_field_fill_rate_variation_license_number_warning');
        $this->assertOnlyOneActiveBatch();
    }

    public function test_it_deactivates_the_previous_active_batch_and_keeps_only_the_new_one_active_when_comparative_only_emits_warnings(): void
    {
        $previousBatch = $this->createActiveBatchWithSummary(
            rawRowsCount: 10,
            validRowsCount: 10,
            invalidRowsCount: 0,
            requiredFieldFillRates: [
                'license_number' => 100,
                'last_name' => 100,
                'first_name' => 100,
            ],
        );

        $this->enableComparativeValidation(
            totalRowsVariation: ['warning_percent' => 10, 'reject_percent' => 50],
            validRowsVariation: ['warning_percent' => 100, 'reject_percent' => 100],
            invalidRatioVariation: ['warning_percent' => 100, 'reject_percent' => 100],
            fieldFillRateVariation: ['warning_percent' => 100, 'reject_percent' => 100],
        );

        $this->fakeHtmlTable([
            ['LIC-001', 'Dupont', 'Jean', 'Senior'],
            ['LIC-002', 'Martin', 'Claire', 'Junior'],
            ['LIC-003', 'Bernard', 'Luc', 'Cadet'],
            ['LIC-004', 'Petit', 'Anne', 'Minime'],
            ['LIC-005', 'Durand', 'Paul', 'Senior'],
            ['LIC-006', 'Moreau', 'Eva', 'Junior'],
            ['LIC-007', 'Simon', 'Hugo', 'Cadet'],
            ['LIC-008', 'Laurent', 'Nina', 'Minime'],
            ['LIC-009', 'Michel', 'Leo', 'Senior'],
        ]);

        $batch = $this->runImportSuccessfully();

        $previousBatch->refresh();
        $batch->refresh();

        $this->assertFalse($previousBatch->is_active);
        $this->assertTrue($batch->is_active);
        $this->assertOnlyOneActiveBatch();
        $this->assertSame($previousBatch->id, data_get($batch->summary, 'comparative_validation.previous_batch_id'));
    }

    public function test_it_stores_full_comparative_summary_for_a_second_real_import(): void
    {
        $previousBatch = $this->createActiveBatchWithSummary(
            rawRowsCount: 4,
            validRowsCount: 4,
            invalidRowsCount: 0,
            requiredFieldFillRates: [
                'license_number' => 100,
                'last_name' => 100,
                'first_name' => 100,
            ],
        );

        $this->enableComparativeValidation(
            totalRowsVariation: ['warning_percent' => 10, 'reject_percent' => 50],
            validRowsVariation: ['warning_percent' => 10, 'reject_percent' => 50],
            invalidRatioVariation: ['warning_percent' => 10, 'reject_percent' => 50],
            fieldFillRateVariation: ['warning_percent' => 10, 'reject_percent' => 50],
        );

        $this->fakeHtmlTable([
            ['LIC-001', 'Dupont', 'Jean', 'Senior'],
            ['LIC-002', 'Martin', 'Claire', 'Junior'],
            ['LIC-003', 'Bernard', 'Luc', 'Cadet'],
            ['LIC-004', 'Petit', 'Anne', 'Minime'],
        ]);

        $batch = $this->runImportSuccessfully();

        $summary = is_array($batch->summary) ? $batch->summary : [];
        $comparative = is_array($summary['comparative_validation'] ?? null)
            ? $summary['comparative_validation']
            : [];

        $this->assertTrue((bool) ($comparative['enabled'] ?? false));
        $this->assertFalse((bool) ($comparative['skipped'] ?? true));
        $this->assertSame($previousBatch->id, $comparative['previous_batch_id'] ?? null);

        $this->assertSame(4, $comparative['current']['raw_rows_count'] ?? null);
        $this->assertSame(4, $comparative['current']['valid_rows_count'] ?? null);
        $this->assertSame(0, $comparative['current']['invalid_rows_count'] ?? null);
        $this->assertSame(0, $comparative['current']['invalid_ratio_percent'] ?? null);
        $this->assertSame([
            'license_number' => 100,
            'last_name' => 100,
            'first_name' => 100,
        ], $comparative['current']['required_field_fill_rates'] ?? null);

        $this->assertSame(4, $comparative['previous']['raw_rows_count'] ?? null);
        $this->assertSame(4, $comparative['previous']['valid_rows_count'] ?? null);
        $this->assertSame(0, $comparative['previous']['invalid_rows_count'] ?? null);
        $this->assertSame(0, $comparative['previous']['invalid_ratio_percent'] ?? null);
        $this->assertSame([
            'license_number' => 100,
            'last_name' => 100,
            'first_name' => 100,
        ], $comparative['previous']['required_field_fill_rates'] ?? null);
    }

    public function test_it_stores_execution_and_activation_summary_for_a_second_real_import(): void
    {
        $this->createActiveBatchWithSummary(
            rawRowsCount: 2,
            validRowsCount: 2,
            invalidRowsCount: 0,
            requiredFieldFillRates: [
                'license_number' => 100,
                'last_name' => 100,
                'first_name' => 100,
            ],
        );

        $this->enableComparativeValidation();

        $this->fakeHtmlTable([
            ['LIC-001', 'Dupont', 'Jean', 'Senior'],
            ['LIC-002', 'Martin', 'Claire', 'Junior'],
        ]);

        $batch = $this->runImportSuccessfully();

        $summary = is_array($batch->summary) ? $batch->summary : [];
        $execution = is_array($summary['execution'] ?? null) ? $summary['execution'] : [];
        $activation = is_array($summary['activation'] ?? null) ? $summary['activation'] : [];

        $this->assertTrue((bool) ($execution['finished'] ?? false));
        $this->assertSame('succeeded', $execution['result'] ?? null);
        $this->assertSame('activated', $execution['final_outcome'] ?? null);

        $this->assertTrue((bool) ($activation['attempted'] ?? false));
        $this->assertTrue((bool) ($activation['activated'] ?? false));
        $this->assertSame('auto_activate_when_valid', $activation['reason'] ?? null);
    }

    public function test_it_stores_execution_and_activation_meta_for_a_second_real_import(): void
    {
        $this->createActiveBatchWithSummary(
            rawRowsCount: 2,
            validRowsCount: 2,
            invalidRowsCount: 0,
            requiredFieldFillRates: [
                'license_number' => 100,
                'last_name' => 100,
                'first_name' => 100,
            ],
        );

        $this->enableComparativeValidation();

        $this->fakeHtmlTable([
            ['LIC-001', 'Dupont', 'Jean', 'Senior'],
            ['LIC-002', 'Martin', 'Claire', 'Junior'],
        ]);

        $batch = $this->runImportSuccessfully();

        $meta = is_array($batch->meta) ? $batch->meta : [];
        $execution = is_array($meta['execution'] ?? null) ? $meta['execution'] : [];
        $activation = is_array($meta['activation'] ?? null) ? $meta['activation'] : [];

        $this->assertTrue((bool) ($execution['finished'] ?? false));
        $this->assertSame('succeeded', $execution['result'] ?? null);
        $this->assertSame('activated', $execution['final_outcome'] ?? null);
        $this->assertNotNull($execution['requested_at'] ?? null);
        $this->assertNotNull($execution['started_at'] ?? null);
        $this->assertNotNull($execution['finished_at'] ?? null);
        $this->assertFalse((bool) ($execution['dry_run'] ?? true));

        $this->assertTrue((bool) ($activation['attempted'] ?? false));
        $this->assertTrue((bool) ($activation['activated'] ?? false));
        $this->assertSame('auto_activate_when_valid', $activation['reason'] ?? null);
        $this->assertFalse((bool) ($activation['effective_dry_run'] ?? true));
    }

    public function test_it_references_the_previous_active_batch_in_the_second_real_import_summary(): void
    {
        $previousBatch = $this->createActiveBatchWithSummary(
            rawRowsCount: 3,
            validRowsCount: 3,
            invalidRowsCount: 0,
            requiredFieldFillRates: [
                'license_number' => 100,
                'last_name' => 100,
                'first_name' => 100,
            ],
        );

        $this->enableComparativeValidation();

        $this->fakeHtmlTable([
            ['LIC-001', 'Dupont', 'Jean', 'Senior'],
            ['LIC-002', 'Martin', 'Claire', 'Junior'],
            ['LIC-003', 'Bernard', 'Luc', 'Cadet'],
        ]);

        $batch = $this->runImportSuccessfully();

        $summary = is_array($batch->summary) ? $batch->summary : [];
        $comparative = is_array($summary['comparative_validation'] ?? null)
            ? $summary['comparative_validation']
            : [];

        $this->assertSame($previousBatch->id, $comparative['previous_batch_id'] ?? null);
        $this->assertSame(3, $comparative['previous']['raw_rows_count'] ?? null);
        $this->assertSame(3, $comparative['previous']['valid_rows_count'] ?? null);
        $this->assertSame(0, $comparative['previous']['invalid_rows_count'] ?? null);
    }

    public function test_it_deactivates_the_previous_batch_and_activates_the_new_one_on_a_second_real_import(): void
    {
        $previousBatch = $this->createActiveBatchWithSummary(
            rawRowsCount: 2,
            validRowsCount: 2,
            invalidRowsCount: 0,
            requiredFieldFillRates: [
                'license_number' => 100,
                'last_name' => 100,
                'first_name' => 100,
            ],
        );

        $this->enableComparativeValidation();

        $this->fakeHtmlTable([
            ['LIC-001', 'Dupont', 'Jean', 'Senior'],
            ['LIC-002', 'Martin', 'Claire', 'Junior'],
        ]);

        $batch = $this->runImportSuccessfully();

        $previousBatch->refresh();
        $batch->refresh();

        $this->assertFalse($previousBatch->is_active);
        $this->assertTrue($batch->is_active);
        $this->assertNotNull($batch->activated_at);
        $this->assertOnlyOneActiveBatch();
    }

    public function test_it_skips_comparative_validation_when_feature_is_disabled_even_if_previous_active_batch_exists(): void
    {
        $previousBatch = $this->createActiveBatchWithSummary(
            rawRowsCount: 4,
            validRowsCount: 4,
            invalidRowsCount: 0,
            requiredFieldFillRates: [
                'license_number' => 100,
                'last_name' => 100,
                'first_name' => 100,
            ],
        );

        config()->set('license_import.validation.comparative.enabled', false);

        $this->fakeHtmlTable([
            ['LIC-001', 'Dupont', 'Jean', 'Senior'],
            ['LIC-002', 'Martin', 'Claire', 'Junior'],
            ['LIC-003', 'Bernard', 'Luc', 'Cadet'],
        ]);

        $batch = $this->runImportSuccessfully();

        $this->assertBatchCounts($batch, rawRowsCount: 3, validRowsCount: 3, invalidRowsCount: 0);
        $this->assertBatchActivated($batch);
        $this->assertOnlyOneActiveBatch();

        $previousBatch->refresh();
        $this->assertFalse($previousBatch->is_active);

        $summary = is_array($batch->summary) ? $batch->summary : [];

        $this->assertArrayNotHasKey('comparative_validation', $summary);
    }

    public function test_it_does_not_create_any_comparative_issue_when_comparative_validation_is_disabled(): void
    {
        $this->createActiveBatchWithSummary(
            rawRowsCount: 10,
            validRowsCount: 10,
            invalidRowsCount: 0,
            requiredFieldFillRates: [
                'license_number' => 100,
                'last_name' => 100,
                'first_name' => 100,
            ],
        );

        config()->set('license_import.validation.comparative.enabled', false);
        config()->set('license_import.validation.minimal.required_field_fill_rate_percent', [
            'license_number' => 0,
            'last_name' => 100,
            'first_name' => 100,
        ]);
        config()->set('license_import.validation.minimal.minimum_valid_ratio_percent', 50);
        config()->set('license_import.validation.minimal.maximum_invalid_ratio_percent', 50);

        $this->fakeHtmlTable([
            ['LIC-001', 'Dupont', 'Jean', 'Senior'],
            ['LIC-002', 'Martin', 'Claire', 'Junior'],
            ['LIC-003', 'Bernard', 'Luc', 'Cadet'],
            ['LIC-004', 'Petit', 'Anne', 'Minime'],
            ['LIC-005', 'Moreau', 'Paul', 'Senior'],
            ['LIC-006', 'Simon', 'Lea', 'Junior'],
            ['LIC-007', 'Roux', 'Marc', 'Cadet'],
            ['LIC-008', 'Lefevre', 'Julie', 'Minime'],
            ['', 'Robert', 'Nina', 'Senior'],
            ['', 'Garcia', 'Hugo', 'Junior'],
        ]);

        $batch = $this->runImportSuccessfully();

        $this->assertBatchCounts($batch, rawRowsCount: 10, validRowsCount: 8, invalidRowsCount: 2);
        $this->assertBatchActivated($batch);

        $comparativeIssuesCount = $batch->issues()
            ->where('code', 'like', 'comparative_%')
            ->count();

        $this->assertSame(0, $comparativeIssuesCount);
    }

    public function test_it_activates_new_batch_based_only_on_minimal_validation_when_comparative_validation_is_disabled(): void
    {
        $previousBatch = $this->createActiveBatchWithSummary(
            rawRowsCount: 100,
            validRowsCount: 100,
            invalidRowsCount: 0,
            requiredFieldFillRates: [
                'license_number' => 100,
                'last_name' => 100,
                'first_name' => 100,
            ],
        );

        config()->set('license_import.validation.comparative.enabled', false);
        config()->set('license_import.validation.minimal.required_field_fill_rate_percent', [
            'license_number' => 0,
            'last_name' => 100,
            'first_name' => 100,
        ]);
        config()->set('license_import.validation.minimal.minimum_valid_ratio_percent', 50);
        config()->set('license_import.validation.minimal.maximum_invalid_ratio_percent', 50);

        $this->fakeHtmlTable([
            ['LIC-001', 'Dupont', 'Jean', 'Senior'],
            ['LIC-002', 'Martin', 'Claire', 'Junior'],
            ['', 'Bernard', 'Luc', 'Cadet'],
            ['', 'Petit', 'Anne', 'Minime'],
        ]);

        $batch = $this->runImportSuccessfully();

        $this->assertBatchCounts($batch, rawRowsCount: 4, validRowsCount: 2, invalidRowsCount: 2);
        $this->assertBatchActivated($batch);
        $this->assertOnlyOneActiveBatch();

        $previousBatch->refresh();
        $this->assertFalse($previousBatch->is_active);

        $this->assertSame(0, $batch->issues()->where('code', 'like', 'comparative_%')->count());
    }

    public function test_it_keeps_minimal_validation_summary_only_when_comparative_validation_is_disabled(): void
    {
        $this->createActiveBatchWithSummary(
            rawRowsCount: 6,
            validRowsCount: 6,
            invalidRowsCount: 0,
            requiredFieldFillRates: [
                'license_number' => 100,
                'last_name' => 100,
                'first_name' => 100,
            ],
        );

        config()->set('license_import.validation.comparative.enabled', false);

        $this->fakeHtmlTable([
            ['LIC-001', 'Dupont', 'Jean', 'Senior'],
            ['LIC-002', 'Martin', 'Claire', 'Junior'],
        ]);

        $batch = $this->runImportSuccessfully();

        $summary = is_array($batch->summary) ? $batch->summary : [];

        $this->assertArrayHasKey('minimal_validation', $summary);
        $this->assertArrayNotHasKey('comparative_validation', $summary);

        $minimal = is_array($summary['minimal_validation'] ?? null)
            ? $summary['minimal_validation']
            : [];

        $this->assertSame(2, $minimal['raw_rows_count'] ?? null);
        $this->assertSame(2, $minimal['valid_rows_count'] ?? null);
        $this->assertSame(0, $minimal['invalid_rows_count'] ?? null);
    }

    public function test_it_allows_duplicate_license_numbers_without_creating_issue_when_policy_is_allowed(): void
    {
        config()->set('license_import.validation.minimal.duplicate_license_number_policy', 'allowed');

        $this->setMinimalValidationForRatioTests(
            minimumValidRatioPercent: 100,
            maximumInvalidRatioPercent: 0,
            requiredFieldFillRatePercent: [
                'license_number' => 100,
                'last_name' => 100,
                'first_name' => 100,
            ],
        );

        $this->fakeHtmlTable([
            ['LIC-001', 'Dupont', 'Jean', 'Senior'],
            ['LIC-001', 'Martin', 'Claire', 'Junior'],
        ]);

        $batch = $this->runImportSuccessfully();

        $this->assertBatchCounts($batch, rawRowsCount: 2, validRowsCount: 2, invalidRowsCount: 0);
        $this->assertBatchActivated($batch);
        $this->assertSnapshotValidity($batch, [true, true]);

        $this->assertFalse($batch->issues()->where('code', 'duplicate_license_number_warning')->exists());
        $this->assertFalse($batch->issues()->where('code', 'duplicate_license_number_rejected')->exists());

        $summary = is_array($batch->summary) ? $batch->summary : [];
        $minimal = is_array($summary['minimal_validation'] ?? null)
            ? $summary['minimal_validation']
            : [];

        $this->assertSame(['LIC-001'], $minimal['duplicate_license_numbers'] ?? []);
    }

    public function test_it_creates_warning_issue_for_duplicate_license_numbers_when_policy_is_warning(): void
    {
        config()->set('license_import.validation.minimal.duplicate_license_number_policy', 'warning');

        $this->setMinimalValidationForRatioTests(
            minimumValidRatioPercent: 100,
            maximumInvalidRatioPercent: 0,
            requiredFieldFillRatePercent: [
                'license_number' => 100,
                'last_name' => 100,
                'first_name' => 100,
            ],
        );

        $this->fakeHtmlTable([
            ['LIC-001', 'Dupont', 'Jean', 'Senior'],
            ['LIC-001', 'Martin', 'Claire', 'Junior'],
        ]);

        $batch = $this->runImportSuccessfully();

        $this->assertBatchCounts($batch, rawRowsCount: 2, validRowsCount: 2, invalidRowsCount: 0);
        $this->assertBatchActivated($batch);
        $this->assertSnapshotValidity($batch, [true, true]);
        $this->assertBatchHasIssueCode($batch, 'duplicate_license_number_warning');

        $warningIssue = $batch->issues()
            ->where('code', 'duplicate_license_number_warning')
            ->first();

        $this->assertNotNull($warningIssue);

        $context = is_array($warningIssue->context) ? $warningIssue->context : [];

        $this->assertSame('LIC-001', $context['license_number'] ?? null);
        $this->assertSame([0, 1], $context['row_indexes'] ?? null);
        $this->assertSame('warning', $context['policy'] ?? null);

        $summary = is_array($batch->summary) ? $batch->summary : [];
        $minimal = is_array($summary['minimal_validation'] ?? null)
            ? $summary['minimal_validation']
            : [];

        $this->assertSame(['LIC-001'], $minimal['duplicate_license_numbers'] ?? []);
    }

    public function test_it_rejects_duplicate_license_numbers_when_policy_is_reject(): void
    {
        config()->set('license_import.validation.minimal.duplicate_license_number_policy', 'reject');

        $this->setMinimalValidationForRatioTests(
            minimumValidRatioPercent: 100,
            maximumInvalidRatioPercent: 100,
            requiredFieldFillRatePercent: [
                'license_number' => 100,
                'last_name' => 100,
                'first_name' => 100,
            ],
        );

        $this->fakeHtmlTable([
            ['LIC-001', 'Dupont', 'Jean', 'Senior'],
            ['LIC-001', 'Martin', 'Claire', 'Junior'],
        ]);

        $batch = $this->runImportExpectingRuntimeException(
            'Minimal validation failed for the current import batch.',
        );

        $this->assertBatchCounts($batch, rawRowsCount: 2, validRowsCount: 0, invalidRowsCount: 2);
        $this->assertBatchIsFailed($batch);
        $this->assertSnapshotValidity($batch, [false, false]);
        $this->assertBatchHasIssueCode($batch, 'duplicate_license_number_rejected');

        $snapshots = $batch->snapshots()->orderBy('row_index')->get();

        $this->assertCount(2, $snapshots);
        $this->assertContains('duplicate_license_number', is_array($snapshots[0]->validation_flags) ? $snapshots[0]->validation_flags : []);
        $this->assertContains('duplicate_license_number', is_array($snapshots[1]->validation_flags) ? $snapshots[1]->validation_flags : []);

        $errorIssue = $batch->issues()
            ->where('code', 'duplicate_license_number_rejected')
            ->first();

        $this->assertNotNull($errorIssue);

        $context = is_array($errorIssue->context) ? $errorIssue->context : [];

        $this->assertSame('LIC-001', $context['license_number'] ?? null);
        $this->assertSame([0, 1], $context['row_indexes'] ?? null);
        $this->assertSame('reject', $context['policy'] ?? null);

        $summary = is_array($batch->summary) ? $batch->summary : [];
        $minimal = is_array($summary['minimal_validation'] ?? null)
            ? $summary['minimal_validation']
            : [];

        $this->assertSame(['LIC-001'], $minimal['duplicate_license_numbers'] ?? []);
    }

    public function test_it_keeps_non_duplicated_rows_valid_and_rejects_only_duplicated_rows_when_policy_is_reject(): void
    {
        config()->set('license_import.validation.minimal.duplicate_license_number_policy', 'reject');

        $this->setMinimalValidationForRatioTests(
            minimumValidRatioPercent: 50,
            maximumInvalidRatioPercent: 100,
            requiredFieldFillRatePercent: [
                'license_number' => 100,
                'last_name' => 100,
                'first_name' => 100,
            ],
        );

        $this->fakeHtmlTable([
            ['LIC-001', 'Dupont', 'Jean', 'Senior'],
            ['LIC-001', 'Martin', 'Claire', 'Junior'],
            ['LIC-002', 'Bernard', 'Luc', 'Cadet'],
        ]);

        $batch = $this->runImportExpectingRuntimeException(
            'Minimal validation failed for the current import batch.',
        );

        $this->assertBatchCounts($batch, rawRowsCount: 3, validRowsCount: 1, invalidRowsCount: 2);
        $this->assertBatchIsFailed($batch);
        $this->assertSnapshotValidity($batch, [false, false, true]);
        $this->assertBatchHasIssueCode($batch, 'duplicate_license_number_rejected');
    }

    public function test_it_does_not_report_duplicate_license_number_when_values_are_distinct(): void
    {
        config()->set('license_import.validation.minimal.duplicate_license_number_policy', 'warning');

        $this->setMinimalValidationForRatioTests(
            minimumValidRatioPercent: 100,
            maximumInvalidRatioPercent: 0,
            requiredFieldFillRatePercent: [
                'license_number' => 100,
                'last_name' => 100,
                'first_name' => 100,
            ],
        );

        $this->fakeHtmlTable([
            ['LIC-001', 'Dupont', 'Jean', 'Senior'],
            ['LIC-002', 'Martin', 'Claire', 'Junior'],
        ]);

        $batch = $this->runImportSuccessfully();

        $this->assertBatchCounts($batch, rawRowsCount: 2, validRowsCount: 2, invalidRowsCount: 0);
        $this->assertBatchActivated($batch);

        $this->assertFalse($batch->issues()->where('code', 'duplicate_license_number_warning')->exists());
        $this->assertFalse($batch->issues()->where('code', 'duplicate_license_number_rejected')->exists());

        $summary = is_array($batch->summary) ? $batch->summary : [];
        $minimal = is_array($summary['minimal_validation'] ?? null)
            ? $summary['minimal_validation']
            : [];

        $this->assertSame([], $minimal['duplicate_license_numbers'] ?? []);
    }

    public function test_it_stores_parsed_source_columns_on_batch(): void
    {
        $this->fakeSuccessfulHtmlResponse(
            $this->validTelematHtml(),
        );

        $batch = $this->runImportSuccessfully();

        $batch->refresh();

        $this->assertSame(
            ['Numéro', 'Nom', 'Prénom', 'Catégorie'],
            $batch->source_columns,
        );
    }

    public function test_it_stores_a_non_empty_source_fingerprint_on_batch(): void
    {
        $this->fakeHtmlTable([
            ['LIC-001', 'Dupont', 'Jean', 'Senior'],
            ['LIC-002', 'Martin', 'Claire', 'Junior'],
        ]);

        $batch = $this->runImportSuccessfully();

        $batch->refresh();

        $this->assertIsString($batch->source_fingerprint);
        $this->assertNotSame('', $batch->source_fingerprint);
        $this->assertSame(64, strlen($batch->source_fingerprint));
    }

    public function test_it_keeps_the_same_source_fingerprint_for_two_identical_imports(): void
    {
        $this->fakeHtmlTable([
            ['LIC-001', 'Dupont', 'Jean', 'Senior'],
            ['LIC-002', 'Martin', 'Claire', 'Junior'],
        ]);

        $firstBatch = $this->runImportSuccessfully();

        $this->fakeHtmlTable([
            ['LIC-001', 'Dupont', 'Jean', 'Senior'],
            ['LIC-002', 'Martin', 'Claire', 'Junior'],
        ]);

        $secondBatch = $this->runImportSuccessfully();

        $this->assertSame($firstBatch->source_fingerprint, $secondBatch->source_fingerprint);
    }

    public function test_it_changes_source_fingerprint_when_dataset_changes(): void
    {
        $this->setMinimalValidationForRatioTests(
            minimumValidRatioPercent: 0,
            maximumInvalidRatioPercent: 100,
            requiredFieldFillRatePercent: [
                'license_number' => 0,
                'last_name' => 0,
                'first_name' => 0,
            ],
        );

        $this->enableComparativeValidation(
            totalRowsVariation: ['warning_percent' => 100, 'reject_percent' => 100],
            validRowsVariation: ['warning_percent' => 100, 'reject_percent' => 100],
            invalidRatioVariation: ['warning_percent' => 100, 'reject_percent' => 100],
            fieldFillRateVariation: ['warning_percent' => 100, 'reject_percent' => 100],
        );

        $this->swapTelematFetcher(new \Tests\Integration\Domain\LicenseImport\Fakes\SuccessfulTelematFetcher(
            $this->makeHtmlTable(
                ['Numéro', 'Nom', 'Prénom', 'Catégorie'],
                [
                    ['LIC-001', 'Dupont', 'Jean', 'Senior'],
                    ['LIC-002', 'Martin', 'Claire', 'Junior'],
                ],
            ),
        ));

        $firstBatch = $this->runImportSuccessfully();
        $firstBatch->refresh();

        $this->swapTelematFetcher(new \Tests\Integration\Domain\LicenseImport\Fakes\SuccessfulTelematFetcher(
            $this->makeHtmlTable(
                ['Numéro', 'Nom', 'Prénom', 'Catégorie'],
                [
                    ['LIC-001', 'Dupont', 'Jean', 'Senior'],
                    ['LIC-999', 'Durand', 'Alice', 'Elite'],
                ],
            ),
        ));

        $secondBatch = $this->runImportSuccessfully();
        $secondBatch->refresh();

        $this->assertNotSame($firstBatch->getKey(), $secondBatch->getKey());
        $this->assertSame(2, LicenseImportBatch::query()->count());
        $this->assertSame(2, $secondBatch->snapshots()->count());

        $firstRows = $firstBatch->snapshots()
            ->orderBy('row_index')
            ->get(['license_number', 'last_name', 'first_name', 'category'])
            ->map(static fn ($snapshot): array => [
                'license_number' => $snapshot->license_number,
                'last_name' => $snapshot->last_name,
                'first_name' => $snapshot->first_name,
                'category' => $snapshot->category,
            ])
            ->values()
            ->all();

        $secondRows = $secondBatch->snapshots()
            ->orderBy('row_index')
            ->get(['license_number', 'last_name', 'first_name', 'category'])
            ->map(static fn ($snapshot): array => [
                'license_number' => $snapshot->license_number,
                'last_name' => $snapshot->last_name,
                'first_name' => $snapshot->first_name,
                'category' => $snapshot->category,
            ])
            ->values()
            ->all();

        $this->assertSame([
            [
                'license_number' => 'LIC-001',
                'last_name' => 'Dupont',
                'first_name' => 'Jean',
                'category' => 'Senior',
            ],
            [
                'license_number' => 'LIC-002',
                'last_name' => 'Martin',
                'first_name' => 'Claire',
                'category' => 'Junior',
            ],
        ], $firstRows);

        $this->assertSame([
            [
                'license_number' => 'LIC-001',
                'last_name' => 'Dupont',
                'first_name' => 'Jean',
                'category' => 'Senior',
            ],
            [
                'license_number' => 'LIC-999',
                'last_name' => 'Durand',
                'first_name' => 'Alice',
                'category' => 'Elite',
            ],
        ], $secondRows);

        $this->assertNotNull($firstBatch->source_fingerprint);
        $this->assertNotNull($secondBatch->source_fingerprint);

        $this->assertNotSame(
            $firstBatch->source_fingerprint,
            $secondBatch->source_fingerprint,
        );
    }

    public function test_it_keeps_the_same_source_fingerprint_when_same_rows_are_imported_in_different_order(): void
    {
        $this->fakeHtmlTable([
            ['LIC-001', 'Dupont', 'Jean', 'Senior'],
            ['LIC-002', 'Martin', 'Claire', 'Junior'],
            ['LIC-003', 'Bernard', 'Luc', 'Cadet'],
        ]);

        $firstBatch = $this->runImportSuccessfully();

        $this->fakeHtmlTable([
            ['LIC-003', 'Bernard', 'Luc', 'Cadet'],
            ['LIC-001', 'Dupont', 'Jean', 'Senior'],
            ['LIC-002', 'Martin', 'Claire', 'Junior'],
        ]);

        $secondBatch = $this->runImportSuccessfully();

        $this->assertSame($firstBatch->source_fingerprint, $secondBatch->source_fingerprint);
    }

    public function test_it_stores_a_source_row_hash_for_each_snapshot(): void
    {
        $this->fakeHtmlTable([
            ['LIC-001', 'Dupont', 'Jean', 'Senior'],
            ['LIC-002', 'Martin', 'Claire', 'Junior'],
        ]);

        $batch = $this->runImportSuccessfully();

        $snapshots = $batch->snapshots()
            ->orderBy('row_index')
            ->get();

        $this->assertCount(2, $snapshots);

        foreach ($snapshots as $snapshot) {
            $this->assertIsString($snapshot->source_row_hash);
            $this->assertNotSame('', $snapshot->source_row_hash);
            $this->assertSame(64, strlen($snapshot->source_row_hash));
        }

        $this->assertNotSame($snapshots[0]->source_row_hash, $snapshots[1]->source_row_hash);
    }

    public function test_it_stores_source_row_and_normalized_source_row_in_snapshot_extra_data(): void
    {
        $this->fakeSuccessfulHtmlResponse(
            $this->telematHtmlWithSingleValidRow(),
        );

        $batch = $this->runImportSuccessfully();

        $snapshot = $batch->snapshots()
            ->orderBy('row_index')
            ->first();

        $this->assertNotNull($snapshot);

        $extraData = is_array($snapshot->extra_data) ? $snapshot->extra_data : [];

        $sourceRow = is_array($extraData['source_row'] ?? null)
            ? $extraData['source_row']
            : [];

        $normalizedSourceRow = is_array($extraData['normalized_source_row'] ?? null)
            ? $extraData['normalized_source_row']
            : [];

        $this->assertSame([
            'Numéro' => '191100 S',
            'Nom' => 'ARROUAS',
            'Prénom' => 'ISABELLE',
            'Catégorie' => 'Decouverte',
        ], $sourceRow);

        $this->assertSame([
            'numero' => '191100 S',
            'nom' => 'ARROUAS',
            'prenom' => 'ISABELLE',
            'categorie' => 'Decouverte',
        ], $normalizedSourceRow);
    }

    public function test_it_persists_row_indexes_in_sequential_order(): void
    {
        $this->fakeHtmlTable([
            ['LIC-001', 'Dupont', 'Jean', 'Senior'],
            ['LIC-002', 'Martin', 'Claire', 'Junior'],
            ['LIC-003', 'Bernard', 'Luc', 'Cadet'],
        ]);

        $batch = $this->runImportSuccessfully();

        $rowIndexes = $batch->snapshots()
            ->orderBy('row_index')
            ->pluck('row_index')
            ->map(static fn ($value): int => (int) $value)
            ->all();

        $this->assertSame([0, 1, 2], $rowIndexes);
    }

    public function test_it_keeps_source_columns_based_on_parsed_headers_even_when_values_are_empty_on_some_rows(): void
    {
        $this->setMinimalValidationForRatioTests(
            minimumValidRatioPercent: 0,
            maximumInvalidRatioPercent: 100,
            requiredFieldFillRatePercent: [
                'license_number' => 0,
                'last_name' => 0,
                'first_name' => 0,
            ],
        );

        $this->fakeHtmlTable([
            ['LIC-001', 'Dupont', 'Jean', 'Senior'],
            ['LIC-002', '', 'Claire', 'Junior'],
        ]);

        $batch = $this->runImportSuccessfully();

        $batch->refresh();

        $this->assertSame(
            ['Numéro', 'Nom', 'Prénom', 'Catégorie'],
            $batch->source_columns,
        );
    }

    public function test_it_ignores_repeated_header_row_inside_table_body(): void
    {
        $this->fakeSuccessfulHtmlResponse(
            $this->telematHtmlWithRepeatedHeaderRowInsideBody()
        );

        $batch = $this->runImportSuccessfully();

        $this->assertBatchActivated($batch);
        $this->assertBatchCounts($batch, rawRowsCount: 2, validRowsCount: 2, invalidRowsCount: 0);
        $this->assertSame(2, $batch->snapshots()->count());

        $rows = $batch->snapshots()
            ->orderBy('row_index')
            ->get(['license_number', 'last_name', 'first_name', 'category'])
            ->map(static fn ($snapshot): array => [
                'license_number' => $snapshot->license_number,
                'last_name' => $snapshot->last_name,
                'first_name' => $snapshot->first_name,
                'category' => $snapshot->category,
            ])
            ->values()
            ->all();

        $this->assertSame([
            [
                'license_number' => '191100 S',
                'last_name' => 'ARROUAS',
                'first_name' => 'ISABELLE',
                'category' => 'Decouverte',
            ],
            [
                'license_number' => '190399 F',
                'last_name' => 'AUCHART',
                'first_name' => 'THIERRY',
                'category' => 'Decouverte',
            ],
        ], $rows);
    }

    public function test_it_ignores_empty_row_inside_table_body(): void
    {
        $this->fakeSuccessfulHtmlResponse(
            $this->telematHtmlWithEmptyRowInsideBody()
        );

        $batch = $this->runImportSuccessfully();

        $this->assertBatchActivated($batch);
        $this->assertBatchCounts($batch, rawRowsCount: 2, validRowsCount: 2, invalidRowsCount: 0);
        $this->assertSame(2, $batch->snapshots()->count());
    }

    public function test_it_pads_shorter_rows_with_null_values(): void
    {
        $this->setMinimalValidationForRatioTests(
            minimumValidRatioPercent: 0,
            maximumInvalidRatioPercent: 100,
            requiredFieldFillRatePercent: [
                'license_number' => 0,
                'last_name' => 0,
                'first_name' => 0,
            ],
        );

        $this->fakeSuccessfulHtmlResponse(
            $this->telematHtmlWithShorterSecondRow()
        );

        $batch = $this->runImportSuccessfully();

        $this->assertBatchActivated($batch);
        $this->assertBatchCounts($batch, rawRowsCount: 2, validRowsCount: 2, invalidRowsCount: 0);

        $rows = $batch->snapshots()
            ->orderBy('row_index')
            ->get(['license_number', 'last_name', 'first_name', 'category'])
            ->map(static fn ($snapshot): array => [
                'license_number' => $snapshot->license_number,
                'last_name' => $snapshot->last_name,
                'first_name' => $snapshot->first_name,
                'category' => $snapshot->category,
            ])
            ->values()
            ->all();

        $this->assertSame([
            [
                'license_number' => '191100 S',
                'last_name' => 'ARROUAS',
                'first_name' => 'ISABELLE',
                'category' => 'Decouverte',
            ],
            [
                'license_number' => '190399 F',
                'last_name' => 'AUCHART',
                'first_name' => 'THIERRY',
                'category' => null,
            ],
        ], $rows);
    }

    public function test_it_truncates_longer_rows_to_header_count(): void
    {
        $this->fakeSuccessfulHtmlResponse(
            $this->telematHtmlWithLongerSecondRow()
        );

        $batch = $this->runImportSuccessfully();

        $this->assertBatchActivated($batch);
        $this->assertBatchCounts($batch, rawRowsCount: 2, validRowsCount: 2, invalidRowsCount: 0);

        $rows = $batch->snapshots()
            ->orderBy('row_index')
            ->get(['license_number', 'last_name', 'first_name', 'category'])
            ->map(static fn ($snapshot): array => [
                'license_number' => $snapshot->license_number,
                'last_name' => $snapshot->last_name,
                'first_name' => $snapshot->first_name,
                'category' => $snapshot->category,
            ])
            ->values()
            ->all();

        $this->assertSame([
            [
                'license_number' => '191100 S',
                'last_name' => 'ARROUAS',
                'first_name' => 'ISABELLE',
                'category' => 'Decouverte',
            ],
            [
                'license_number' => '190399 F',
                'last_name' => 'AUCHART',
                'first_name' => 'THIERRY',
                'category' => 'Decouverte',
            ],
        ], $rows);
    }

    public function test_it_ignores_body_rows_containing_only_header_cells(): void
    {
        $this->fakeSuccessfulHtmlResponse(
            $this->telematHtmlWithBodyRowContainingOnlyHeaderCells()
        );

        $batch = $this->runImportSuccessfully();

        $this->assertBatchActivated($batch);
        $this->assertBatchCounts($batch, rawRowsCount: 2, validRowsCount: 2, invalidRowsCount: 0);
        $this->assertSame(2, $batch->snapshots()->count());
    }

    public function test_it_can_parse_table_without_thead_when_first_row_uses_th_cells(): void
    {
        $this->fakeSuccessfulHtmlResponse(
            $this->telematHtmlWithoutTheadUsingFirstRowAsHeaders()
        );

        $batch = $this->runImportSuccessfully();

        $this->assertBatchActivated($batch);
        $this->assertBatchCounts($batch, rawRowsCount: 2, validRowsCount: 2, invalidRowsCount: 0);

        $this->assertSame(
            ['Numéro', 'Nom', 'Prénom', 'Catégorie'],
            $batch->source_columns
        );
    }

    public function test_it_can_parse_table_without_thead_when_first_row_uses_td_cells_as_headers(): void
    {
        $this->fakeSuccessfulHtmlResponse(
            $this->telematHtmlWithoutTheadUsingFirstTdRowAsHeaders()
        );

        $batch = $this->runImportSuccessfully();

        $this->assertBatchActivated($batch);
        $this->assertBatchCounts($batch, rawRowsCount: 2, validRowsCount: 2, invalidRowsCount: 0);

        $this->assertSame(
            ['Numéro', 'Nom', 'Prénom', 'Catégorie'],
            $batch->source_columns
        );
    }

    public function test_it_selects_best_matching_table_when_multiple_candidate_tables_exist(): void
    {
        config()->set('license_import.parsing.table_selector', null);
        config()->set('license_import.parsing.use_header_detection_fallback', true);

        $this->fakeSuccessfulHtmlResponse(
            $this->telematHtmlWithMultipleCandidateTablesWhereFallbackMustSelectBestMatch()
        );

        $batch = $this->runImportSuccessfully();

        $this->assertBatchActivated($batch);
        $this->assertBatchCounts($batch, rawRowsCount: 2, validRowsCount: 2, invalidRowsCount: 0);

        $rows = $batch->snapshots()
            ->orderBy('row_index')
            ->get(['license_number', 'last_name', 'first_name'])
            ->map(static fn ($snapshot): array => [
                'license_number' => $snapshot->license_number,
                'last_name' => $snapshot->last_name,
                'first_name' => $snapshot->first_name,
            ])
            ->values()
            ->all();

        $this->assertSame([
            [
                'license_number' => '191100 S',
                'last_name' => 'ARROUAS',
                'first_name' => 'ISABELLE',
            ],
            [
                'license_number' => '190399 F',
                'last_name' => 'AUCHART',
                'first_name' => 'THIERRY',
            ],
        ], $rows);
    }

    public function test_it_keeps_first_matching_table_when_multiple_tables_have_equal_header_score(): void
    {
        config()->set('license_import.parsing.table_selector', null);
        config()->set('license_import.parsing.use_header_detection_fallback', true);

        config()->set('license_import.mapping.required_fields', [
            'license_number',
            'last_name',
            'first_name',
        ]);

        config()->set('license_import.mapping.fields', [
            'license_number' => [
                'sources' => ['numero'],
            ],
            'last_name' => [
                'sources' => ['nom'],
            ],
            'first_name' => [
                'sources' => ['prenom'],
            ],
        ]);

        $this->fakeSuccessfulHtmlResponse(
            $this->telematHtmlWithMultipleEquallyMatchingTablesWhereFallbackMustKeepFirstOne()
        );

        $batch = $this->runImportSuccessfully();

        $this->assertBatchActivated($batch);
        $this->assertBatchCounts($batch, rawRowsCount: 1, validRowsCount: 1, invalidRowsCount: 0);

        $snapshot = $batch->snapshots()->first();

        $this->assertNotNull($snapshot);
        $this->assertSame('FIRST-001', $snapshot->license_number);
        $this->assertSame('PREMIERE', $snapshot->last_name);
        $this->assertSame('TABLE', $snapshot->first_name);
    }

    public function test_it_uses_secondary_alias_when_primary_alias_is_empty(): void
    {
        config()->set('license_import.mapping.fields.license_number.sources', [
            'numero principal',
            'numero',
        ]);

        $this->fakeSuccessfulHtmlResponse(
            $this->telematHtmlWherePrimaryAliasIsEmptyAndSecondaryAliasContainsValue()
        );

        $batch = $this->runImportSuccessfully();

        $this->assertBatchActivated($batch);

        $licenseNumbers = $batch->snapshots()
            ->orderBy('row_index')
            ->pluck('license_number')
            ->values()
            ->all();

        $this->assertSame([
            '191100 S',
            '190399 F',
        ], $licenseNumbers);
    }

    public function test_it_keeps_first_non_empty_value_when_multiple_aliases_match_same_field(): void
    {
        config()->set('license_import.mapping.fields.license_number.sources', [
            'numero principal',
            'numero',
        ]);

        $this->fakeSuccessfulHtmlResponse(
            $this->telematHtmlWhereMultipleAliasesMatchButFirstNonEmptyValueMustWin()
        );

        $batch = $this->runImportSuccessfully();

        $this->assertBatchActivated($batch);

        $licenseNumbers = $batch->snapshots()
            ->orderBy('row_index')
            ->pluck('license_number')
            ->values()
            ->all();

        $this->assertSame([
            'PRIMARY-191100',
            'PRIMARY-190399',
        ], $licenseNumbers);
    }

    public function test_it_keeps_first_non_empty_value_when_multiple_headers_normalize_to_same_key(): void
    {
        $this->fakeSuccessfulHtmlResponse(
            $this->telematHtmlWhereMultipleHeadersNormalizeToSameKeyAndFirstValueMustWin()
        );

        $batch = $this->runImportSuccessfully();

        $this->assertBatchActivated($batch);

        $licenseNumbers = $batch->snapshots()
            ->orderBy('row_index')
            ->pluck('license_number')
            ->values()
            ->all();

        $this->assertSame([
            'PRIMARY-191100',
            'PRIMARY-190399',
        ], $licenseNumbers);
    }

    public function test_it_replaces_empty_first_normalized_value_with_second_non_empty_one(): void
    {
        $this->fakeSuccessfulHtmlResponse(
            $this->telematHtmlWhereMultipleHeadersNormalizeToSameKeyAndSecondValueReplacesEmptyFirstOne()
        );

        $batch = $this->runImportSuccessfully();

        $this->assertBatchActivated($batch);

        $licenseNumbers = $batch->snapshots()
            ->orderBy('row_index')
            ->pluck('license_number')
            ->values()
            ->all();

        $this->assertSame([
            'SECONDARY-191100',
            'SECONDARY-190399',
        ], $licenseNumbers);
    }

    private function latestBatch(): ?LicenseImportBatch
    {
        return LicenseImportBatch::query()
            ->where('source', 'ffbi_telemat')
            ->latest('id')
            ->first();
    }

    private function runImportSuccessfully(): LicenseImportBatch
    {
        $context = $this->makeExecutionContext(dryRun: false);

        /** @var TelematLicenseImportOrchestrator $orchestrator */
        $orchestrator = app(TelematLicenseImportOrchestrator::class);

        $orchestrator->run($context);

        $batch = $this->latestBatch();

        $this->assertNotNull($batch);

        return $batch;
    }

    private function runImportExpectingRuntimeException(string $expectedMessage): LicenseImportBatch
    {
        $context = $this->makeExecutionContext(dryRun: false);

        /** @var TelematLicenseImportOrchestrator $orchestrator */
        $orchestrator = app(TelematLicenseImportOrchestrator::class);

        try {
            $orchestrator->run($context);
            $this->fail('Une RuntimeException était attendue.');
        } catch (RuntimeException $exception) {
            $this->assertSame($expectedMessage, $exception->getMessage());
        }

        $batch = $this->latestBatch();

        $this->assertNotNull($batch);

        return $batch;
    }

    private function setMinimalValidationForRatioTests(
        int $minimumValidRatioPercent,
        int $maximumInvalidRatioPercent,
        array $requiredFieldFillRatePercent = [
            'license_number' => 0,
            'last_name' => 0,
            'first_name' => 0,
        ],
    ): void {
        config()->set('license_import.validation.minimal.minimum_valid_ratio_percent', $minimumValidRatioPercent);
        config()->set('license_import.validation.minimal.maximum_invalid_ratio_percent', $maximumInvalidRatioPercent);
        config()->set('license_import.validation.minimal.required_field_fill_rate_percent', $requiredFieldFillRatePercent);
    }

    private function enableComparativeValidation(
        array $totalRowsVariation = ['warning_percent' => 100, 'reject_percent' => 100],
        array $validRowsVariation = ['warning_percent' => 100, 'reject_percent' => 100],
        array $invalidRatioVariation = ['warning_percent' => 100, 'reject_percent' => 100],
        array $fieldFillRateVariation = ['warning_percent' => 100, 'reject_percent' => 100],
    ): void {
        config()->set('license_import.validation.comparative.enabled', true);
        config()->set('license_import.validation.comparative.total_rows_variation', $totalRowsVariation);
        config()->set('license_import.validation.comparative.valid_rows_variation', $validRowsVariation);
        config()->set('license_import.validation.comparative.invalid_ratio_variation', $invalidRatioVariation);
        config()->set('license_import.validation.comparative.field_fill_rate_variation', $fieldFillRateVariation);
    }

    private function createActiveBatchWithSummary(
        int $rawRowsCount,
        int $validRowsCount,
        int $invalidRowsCount,
        array $requiredFieldFillRates,
    ): LicenseImportBatch {
        return LicenseImportBatch::query()->create([
            'source' => 'ffbi_telemat',
            'status' => BatchStatus::Activated,
            'is_active' => true,
            'activated_at' => now()->subDay(),
            'started_at' => now()->subDay(),
            'finished_at' => now()->subDay(),
            'trigger_type' => TriggerType::Manual,
            'triggered_by_user_id' => null,
            'triggered_by_label' => 'previous-active-batch-with-summary',
            'raw_rows_count' => $rawRowsCount,
            'valid_rows_count' => $validRowsCount,
            'invalid_rows_count' => $invalidRowsCount,
            'error_count' => 0,
            'warning_count' => 0,
            'source_fingerprint' => 'previous-batch-fingerprint-with-summary',
            'source_columns' => ['Numéro', 'Nom', 'Prénom', 'Catégorie'],
            'meta' => [],
            'summary' => [
                'minimal_validation' => [
                    'raw_rows_count' => $rawRowsCount,
                    'valid_rows_count' => $validRowsCount,
                    'invalid_rows_count' => $invalidRowsCount,
                    'valid_ratio_percent' => $rawRowsCount > 0 ? (int) floor(($validRowsCount / $rawRowsCount) * 100) : 0,
                    'invalid_ratio_percent' => $rawRowsCount > 0 ? (int) floor(($invalidRowsCount / $rawRowsCount) * 100) : 0,
                    'required_field_fill_rates' => $requiredFieldFillRates,
                    'duplicate_license_numbers' => [],
                ],
            ],
        ]);
    }

    private function createInactiveBatchWithSummary(
        int $rawRowsCount,
        int $validRowsCount,
        int $invalidRowsCount,
        array $requiredFieldFillRates,
    ): LicenseImportBatch {
        return LicenseImportBatch::query()->create([
            'source' => 'ffbi_telemat',
            'status' => BatchStatus::ValidatedComparative,
            'is_active' => false,
            'activated_at' => null,
            'started_at' => now()->subDay(),
            'finished_at' => now()->subDay(),
            'trigger_type' => TriggerType::Manual,
            'triggered_by_user_id' => null,
            'triggered_by_label' => 'previous-inactive-batch-with-summary',
            'raw_rows_count' => $rawRowsCount,
            'valid_rows_count' => $validRowsCount,
            'invalid_rows_count' => $invalidRowsCount,
            'error_count' => 0,
            'warning_count' => 0,
            'source_fingerprint' => 'previous-inactive-batch-fingerprint',
            'source_columns' => ['Numéro', 'Nom', 'Prénom', 'Catégorie'],
            'meta' => [],
            'summary' => [
                'minimal_validation' => [
                    'raw_rows_count' => $rawRowsCount,
                    'valid_rows_count' => $validRowsCount,
                    'invalid_rows_count' => $invalidRowsCount,
                    'valid_ratio_percent' => $rawRowsCount > 0 ? (int) floor(($validRowsCount / $rawRowsCount) * 100) : 0,
                    'invalid_ratio_percent' => $rawRowsCount > 0 ? (int) floor(($invalidRowsCount / $rawRowsCount) * 100) : 0,
                    'required_field_fill_rates' => $requiredFieldFillRates,
                    'duplicate_license_numbers' => [],
                ],
            ],
        ]);
    }

    private function fakeHtmlTable(array $rows, array $headers = ['Numéro', 'Nom', 'Prénom', 'Catégorie']): void
    {
        $this->fakeSuccessfulHtmlResponse(
            $this->makeHtmlTable($headers, $rows),
        );
    }

    private function makeHtmlTable(array $headers, array $rows): string
    {
        $escape = static fn (string $value): string => htmlspecialchars(
            $value,
            ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5,
            'UTF-8',
            false,
        );

        $thead = '<tr>' . collect($headers)
            ->map(static fn (string $header): string => '<th>' . $escape($header) . '</th>')
            ->implode('') . '</tr>';

        $tbody = collect($rows)
            ->map(static function (array $row) use ($escape): string {
                return '<tr>' . collect($row)
                    ->map(static fn ($cell): string => '<td>' . $escape((string) $cell) . '</td>')
                    ->implode('') . '</tr>';
            })
            ->implode('');

        return <<<HTML
        <!DOCTYPE html>
        <html>
            <head>
                <meta charset="UTF-8">
            </head>
            <body>
                <table id="licenses">
                    <thead>{$thead}</thead>
                    <tbody>{$tbody}</tbody>
                </table>
            </body>
        </html>
        HTML;
    }

    private function runImportExpectingMinimalValidationFailure(): LicenseImportBatch
    {
        $context = $this->makeExecutionContext(dryRun: false);

        /** @var TelematLicenseImportOrchestrator $orchestrator */
        $orchestrator = app(TelematLicenseImportOrchestrator::class);

        try {
            $orchestrator->run($context);
            $this->fail('Une MinimalValidationFailedException était attendue.');
        } catch (\App\Domain\LicenseImport\Pipeline\Exceptions\MinimalValidationFailedException $exception) {
            $this->assertSame(
                'Minimal validation failed for the current import batch.',
                $exception->getMessage(),
            );
        }

        $batch = $this->latestBatch();

        $this->assertNotNull($batch);

        return $batch;
    }

    private function runImportExpectingComparativeValidationFailure(): LicenseImportBatch
    {
        $context = $this->makeExecutionContext(dryRun: false);

        /** @var TelematLicenseImportOrchestrator $orchestrator */
        $orchestrator = app(TelematLicenseImportOrchestrator::class);

        try {
            $orchestrator->run($context);
            $this->fail('Une ComparativeValidationFailedException était attendue.');
        } catch (\App\Domain\LicenseImport\Pipeline\Exceptions\ComparativeValidationFailedException $exception) {
            $this->assertSame(
                'Comparative validation failed for the current import batch.',
                $exception->getMessage(),
            );
        }

        $batch = $this->latestBatch();

        $this->assertNotNull($batch);

        return $batch;
    }

    private function runImportExpectingParseFailure(string $expectedMessage): LicenseImportBatch
    {
        $context = $this->makeExecutionContext(dryRun: false);

        /** @var TelematLicenseImportOrchestrator $orchestrator */
        $orchestrator = app(TelematLicenseImportOrchestrator::class);

        try {
            $orchestrator->run($context);
            $this->fail('Une TelematHtmlParseException était attendue.');
        } catch (\App\Domain\LicenseImport\Pipeline\Exceptions\TelematHtmlParseException $exception) {
            $this->assertSame($expectedMessage, $exception->getMessage());
        }

        $batch = $this->latestBatch();

        $this->assertNotNull($batch);

        return $batch;
    }

    protected function assertBatchIsFailed(LicenseImportBatch $batch): void
    {
        $batch->refresh();

        $this->assertSame(BatchStatus::Failed, $batch->status);
        $this->assertFalse($batch->is_active);
        $this->assertNull($batch->activated_at);
        $this->assertNotNull($batch->started_at);
        $this->assertNotNull($batch->finished_at);

        $meta = is_array($batch->meta) ? $batch->meta : [];
        $executionMeta = is_array($meta['execution'] ?? null) ? $meta['execution'] : [];

        $this->assertTrue($executionMeta['finished'] ?? false);
        $this->assertSame('failed', $executionMeta['result'] ?? null);
        $this->assertSame('failed', $executionMeta['final_outcome'] ?? null);
        $this->assertNotNull($executionMeta['finished_at'] ?? null);

        $summary = is_array($batch->summary) ? $batch->summary : [];
        $executionSummary = is_array($summary['execution'] ?? null) ? $summary['execution'] : [];

        $this->assertTrue($executionSummary['finished'] ?? false);
        $this->assertSame('failed', $executionSummary['result'] ?? null);
        $this->assertSame('failed', $executionSummary['final_outcome'] ?? null);
    }

    protected function assertSnapshotValidity(LicenseImportBatch $batch, array $expected): void
    {
        $actual = $batch->snapshots()
            ->orderBy('row_index')
            ->pluck('is_valid')
            ->map(static fn ($value): bool => (bool) $value)
            ->values()
            ->all();

        $this->assertSame($expected, $actual);
    }

    protected function assertBatchHasIssueCode(LicenseImportBatch $batch, string $expectedCode): void
    {
        $this->assertTrue(
            $batch->issues()->where('code', $expectedCode)->exists(),
            sprintf('Le batch devrait contenir une issue de code "%s".', $expectedCode),
        );
    }
}