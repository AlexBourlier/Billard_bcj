<?php

declare(strict_types=1);

namespace App\Domain\LicenseImport\Activation;

use App\Domain\LicenseImport\DTO\LicenseImportExecutionContext;
use App\Domain\LicenseImport\Enums\BatchStatus;
use App\Domain\LicenseImport\Pipeline\Exceptions\BatchActivationException;
use App\Domain\LicenseImport\Pipeline\TelematBatchLifecycleManager;
use App\Domain\LicenseImport\Pipeline\TelematPipelineLogger;
use App\Models\LicenseImportBatch;
use Illuminate\Support\Facades\DB;

final class TelematBatchActivator
{
    public function __construct(
        private readonly TelematBatchLifecycleManager $batchLifecycleManager,
        private readonly TelematPipelineLogger $logger,
    ) {
    }

    public function activate(LicenseImportBatch $batch, LicenseImportExecutionContext $context): void
    {
        $activationConfig = config('license_import.activation', []);

        if (!is_array($activationConfig)) {
            throw new BatchActivationException('The license_import.activation config must be an array.');
        }

        $autoActivateWhenValid = (bool) ($activationConfig['auto_activate_when_valid'] ?? true);
        $configDryRun = (bool) ($activationConfig['dry_run'] ?? false);
        $effectiveDryRun = $context->dryRun || $configDryRun;

        $finishedAt = now();

        $existingMeta = is_array($batch->meta) ? $batch->meta : [];
        $existingSummary = is_array($batch->summary) ? $batch->summary : [];

        if ($autoActivateWhenValid === false) {
            $batch->forceFill([
                'is_active' => false,
                'activated_at' => null,
                'finished_at' => $finishedAt,
                'meta' => array_replace_recursive($existingMeta, [
                    'execution' => [
                        'finished' => true,
                        'result' => 'succeeded',
                        'final_outcome' => 'auto_activation_disabled',
                        'finished_at' => $finishedAt->toIso8601String(),
                    ],
                    'activation' => [
                        'attempted' => false,
                        'activated' => false,
                        'reason' => 'auto_activation_disabled',
                        'effective_dry_run' => $effectiveDryRun,
                    ],
                ]),
                'summary' => array_replace_recursive($existingSummary, [
                    'execution' => [
                        'finished' => true,
                        'result' => 'succeeded',
                        'final_outcome' => 'auto_activation_disabled',
                    ],
                    'activation' => [
                        'attempted' => false,
                        'activated' => false,
                        'reason' => 'auto_activation_disabled',
                    ],
                ]),
            ])->save();

            $this->batchLifecycleManager->refreshIssueCounters($batch);

            $this->logger->info('license_import.pipeline.activate.skipped_auto_activation_disabled', [
                'batch_id' => $batch->getKey(),
                'source' => $context->source,
            ]);

            return;
        }

        if ($effectiveDryRun) {
            $batch->forceFill([
                'is_active' => false,
                'activated_at' => null,
                'finished_at' => $finishedAt,
                'meta' => array_replace_recursive($existingMeta, [
                    'execution' => [
                        'finished' => true,
                        'result' => 'succeeded',
                        'final_outcome' => 'dry_run',
                        'finished_at' => $finishedAt->toIso8601String(),
                    ],
                    'activation' => [
                        'attempted' => false,
                        'activated' => false,
                        'reason' => 'dry_run',
                        'effective_dry_run' => true,
                    ],
                ]),
                'summary' => array_replace_recursive($existingSummary, [
                    'execution' => [
                        'finished' => true,
                        'result' => 'succeeded',
                        'final_outcome' => 'dry_run',
                    ],
                    'activation' => [
                        'attempted' => false,
                        'activated' => false,
                        'reason' => 'dry_run',
                    ],
                ]),
            ])->save();

            $this->batchLifecycleManager->refreshIssueCounters($batch);

            $this->logger->info('license_import.pipeline.activate.skipped_dry_run', [
                'batch_id' => $batch->getKey(),
                'source' => $context->source,
            ]);

            return;
        }

        DB::transaction(function () use ($batch, $context, $finishedAt, $existingMeta, $existingSummary): void {
            LicenseImportBatch::query()
                ->forSource($context->source)
                ->active()
                ->whereKeyNot($batch->getKey())
                ->update([
                    'is_active' => false,
                ]);

            $batch->forceFill([
                'status' => BatchStatus::Activated,
                'is_active' => true,
                'activated_at' => $finishedAt,
                'finished_at' => $finishedAt,
                'meta' => array_replace_recursive($existingMeta, [
                    'execution' => [
                        'finished' => true,
                        'result' => 'succeeded',
                        'final_outcome' => 'activated',
                        'finished_at' => $finishedAt->toIso8601String(),
                    ],
                    'activation' => [
                        'attempted' => true,
                        'activated' => true,
                        'reason' => 'auto_activate_when_valid',
                        'effective_dry_run' => false,
                    ],
                ]),
                'summary' => array_replace_recursive($existingSummary, [
                    'execution' => [
                        'finished' => true,
                        'result' => 'succeeded',
                        'final_outcome' => 'activated',
                    ],
                    'activation' => [
                        'attempted' => true,
                        'activated' => true,
                        'reason' => 'auto_activate_when_valid',
                    ],
                ]),
            ])->save();
        });

        $batch->refresh();

        $this->batchLifecycleManager->refreshIssueCounters($batch);

        $this->logger->info('license_import.pipeline.activate.completed', [
            'batch_id' => $batch->getKey(),
            'source' => $context->source,
            'activated_at' => optional($batch->activated_at)?->toIso8601String(),
        ]);
    }
}