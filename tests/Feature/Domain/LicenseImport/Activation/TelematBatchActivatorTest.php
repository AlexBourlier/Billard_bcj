<?php

declare(strict_types=1);

namespace Tests\Feature\Domain\LicenseImport\Activation;

use App\Domain\LicenseImport\Activation\TelematBatchActivator;
use App\Domain\LicenseImport\Enums\BatchStatus;
use App\Domain\LicenseImport\Enums\TriggerType;
use App\Domain\LicenseImport\Pipeline\Exceptions\BatchActivationException;
use App\Domain\LicenseImport\Pipeline\TelematBatchLifecycleManager;
use App\Domain\LicenseImport\Pipeline\TelematPipelineLogger;
use App\Models\LicenseImportBatch;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\CreatesLicenseImportExecutionContext;
use Tests\TestCase;

final class TelematBatchActivatorTest extends TestCase
{
    use RefreshDatabase;
    use CreatesLicenseImportExecutionContext;

    public function test_it_throws_when_activation_config_is_not_an_array(): void
    {
        config()->set('license_import.activation', 'invalid');

        $batch = $this->createBatch();

        $activator = $this->makeActivator();

        $this->expectException(BatchActivationException::class);
        $this->expectExceptionMessage('The license_import.activation config must be an array.');

        $activator->activate(
            $batch,
            $this->makeLicenseImportExecutionContext(
                source: 'telemat',
                triggerType: TriggerType::Manual,
            ),
        );
    }

    public function test_it_skips_when_auto_activation_is_disabled(): void
    {
        config()->set('license_import.activation', [
            'auto_activate_when_valid' => false,
            'dry_run' => false,
        ]);

        $batch = $this->createBatch([
            'meta' => ['existing' => ['meta' => true]],
            'summary' => ['existing' => ['summary' => true]],
        ]);

        $activator = $this->makeActivator();

        $activator->activate(
            $batch,
            $this->makeLicenseImportExecutionContext(
                source: 'telemat',
                triggerType: TriggerType::Manual,
                dryRun: false,
            ),
        );

        $batch = $batch->fresh();

        self::assertNotNull($batch);
        self::assertFalse((bool) $batch->is_active);
        self::assertNull($batch->activated_at);
        self::assertNotNull($batch->finished_at);

        self::assertSame('succeeded', data_get($batch->meta, 'execution.result'));
        self::assertSame('auto_activation_disabled', data_get($batch->meta, 'execution.final_outcome'));
        self::assertFalse((bool) data_get($batch->meta, 'activation.attempted'));
        self::assertFalse((bool) data_get($batch->meta, 'activation.activated'));
        self::assertSame('auto_activation_disabled', data_get($batch->meta, 'activation.reason'));
        self::assertFalse((bool) data_get($batch->meta, 'activation.effective_dry_run'));

        self::assertTrue((bool) data_get($batch->meta, 'existing.meta'));
        self::assertTrue((bool) data_get($batch->summary, 'existing.summary'));
    }

    public function test_it_skips_when_context_dry_run_is_true(): void
    {
        config()->set('license_import.activation', [
            'auto_activate_when_valid' => true,
            'dry_run' => false,
        ]);

        $batch = $this->createBatch();

        $activator = $this->makeActivator();

        $activator->activate(
            $batch,
            $this->makeLicenseImportExecutionContext(
                source: 'telemat',
                triggerType: TriggerType::Manual,
                dryRun: true,
            ),
        );

        $batch = $batch->fresh();

        self::assertNotNull($batch);
        self::assertFalse((bool) $batch->is_active);
        self::assertNull($batch->activated_at);
        self::assertNotNull($batch->finished_at);
        self::assertSame('dry_run', data_get($batch->meta, 'execution.final_outcome'));
        self::assertTrue((bool) data_get($batch->meta, 'activation.effective_dry_run'));
        self::assertSame('dry_run', data_get($batch->summary, 'activation.reason'));
    }

    public function test_it_skips_when_config_dry_run_is_true_even_if_context_is_not_dry_run(): void
    {
        config()->set('license_import.activation', [
            'auto_activate_when_valid' => true,
            'dry_run' => true,
        ]);

        $batch = $this->createBatch();

        $activator = $this->makeActivator();

        $activator->activate(
            $batch,
            $this->makeLicenseImportExecutionContext(
                source: 'telemat',
                triggerType: TriggerType::Manual,
                dryRun: false,
            ),
        );

        $batch = $batch->fresh();

        self::assertNotNull($batch);
        self::assertFalse((bool) $batch->is_active);
        self::assertNull($batch->activated_at);
        self::assertSame('dry_run', data_get($batch->meta, 'execution.final_outcome'));
        self::assertTrue((bool) data_get($batch->meta, 'activation.effective_dry_run'));
    }

    public function test_it_activates_current_batch_and_deactivates_previous_active_batches_of_same_source(): void
    {
        config()->set('license_import.activation', [
            'auto_activate_when_valid' => true,
            'dry_run' => false,
        ]);

        $olderActiveSameSource = $this->createBatch([
            'source' => 'telemat',
            'status' => BatchStatus::Activated->value,
            'is_active' => true,
            'activated_at' => CarbonImmutable::parse('2026-04-14T10:00:00+02:00'),
        ]);

        $otherSourceActive = $this->createBatch([
            'source' => 'other-source',
            'status' => BatchStatus::Activated->value,
            'is_active' => true,
            'activated_at' => CarbonImmutable::parse('2026-04-14T10:00:00+02:00'),
        ]);

        $currentBatch = $this->createBatch([
            'source' => 'telemat',
            'status' => BatchStatus::ValidatedComparative->value,
            'is_active' => false,
            'meta' => ['existing' => ['meta' => true]],
            'summary' => ['existing' => ['summary' => true]],
        ]);

        $activator = $this->makeActivator();

        $activator->activate(
            $currentBatch,
            $this->makeLicenseImportExecutionContext(
                source: 'telemat',
                triggerType: TriggerType::Manual,
                dryRun: false,
            ),
        );

        $currentBatch = $currentBatch->fresh();
        $olderActiveSameSource = $olderActiveSameSource->fresh();
        $otherSourceActive = $otherSourceActive->fresh();

        self::assertNotNull($currentBatch);
        self::assertNotNull($olderActiveSameSource);
        self::assertNotNull($otherSourceActive);

        self::assertSame(BatchStatus::Activated, $currentBatch->status);
        self::assertTrue((bool) $currentBatch->is_active);
        self::assertNotNull($currentBatch->activated_at);
        self::assertNotNull($currentBatch->finished_at);

        self::assertSame('activated', data_get($currentBatch->meta, 'execution.final_outcome'));
        self::assertTrue((bool) data_get($currentBatch->meta, 'activation.attempted'));
        self::assertTrue((bool) data_get($currentBatch->meta, 'activation.activated'));
        self::assertFalse((bool) data_get($currentBatch->meta, 'activation.effective_dry_run'));

        self::assertTrue((bool) data_get($currentBatch->summary, 'existing.summary'));
        self::assertTrue((bool) data_get($currentBatch->meta, 'existing.meta'));

        self::assertFalse((bool) $olderActiveSameSource->is_active);
        self::assertTrue((bool) $otherSourceActive->is_active);
    }

    private function makeActivator(): TelematBatchActivator
    {
        return new TelematBatchActivator(
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
            'summary' => null,
        ], $attributes));

        return $batch;
    }
}