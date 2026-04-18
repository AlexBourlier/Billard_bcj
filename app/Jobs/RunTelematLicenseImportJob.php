<?php

namespace App\Jobs;

use App\Domain\LicenseImport\DTO\LicenseImportExecutionContext;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Throwable;

class RunTelematLicenseImportJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * @param array<string, mixed> $payload
     */
    public function __construct(
        public array $payload,
    ) {
        $this->onQueue(config('license_import.job.queue', 'default'));
    }

    public function handle(): void
    {
        $context = LicenseImportExecutionContext::fromArray($this->payload);

        $lockName = (string) config('license_import.lock.name', 'license-import:ffbi-telemat');
        $lockTtlSeconds = (int) config('license_import.lock.ttl_seconds', 900);

        $lock = Cache::lock($lockName, $lockTtlSeconds);

        if (! $lock->get()) {
            Log::info('License import skipped because another import is already running.', [
                'event' => 'license_import.skipped_locked',
                'source' => $context->source,
                'trigger_type' => $context->triggerType->value,
                'triggered_by_user_id' => $context->triggeredByUserId,
                'triggered_by_label' => $context->triggeredByLabel,
                'requested_at' => $context->requestedAt->toIso8601String(),
                'dry_run' => $context->dryRun,
            ]);

            return;
        }

        try {
            Log::info('License import job started.', [
                'event' => 'license_import.job_started',
                'source' => $context->source,
                'trigger_type' => $context->triggerType->value,
                'triggered_by_user_id' => $context->triggeredByUserId,
                'triggered_by_label' => $context->triggeredByLabel,
                'requested_at' => $context->requestedAt->toIso8601String(),
                'dry_run' => $context->dryRun,
            ]);

            /*
             |--------------------------------------------------------------
             | Placeholder orchestrator call
             |--------------------------------------------------------------
             |
             | Ici, on branchera ensuite le vrai service d’orchestration,
             | par exemple :
             |
             */
             app(\App\Domain\LicenseImport\Pipeline\TelematLicenseImportOrchestrator::class)->run($context);

            // TODO: Call the import orchestrator here.

            Log::info('License import job finished.', [
                'event' => 'license_import.job_finished',
                'source' => $context->source,
                'trigger_type' => $context->triggerType->value,
                'triggered_by_user_id' => $context->triggeredByUserId,
                'triggered_by_label' => $context->triggeredByLabel,
                'requested_at' => $context->requestedAt->toIso8601String(),
                'dry_run' => $context->dryRun,
            ]);
        } catch (Throwable $e) {
            Log::error('License import job failed with an unexpected exception.', [
                'event' => 'license_import.job_failed',
                'source' => $context->source,
                'trigger_type' => $context->triggerType->value,
                'triggered_by_user_id' => $context->triggeredByUserId,
                'triggered_by_label' => $context->triggeredByLabel,
                'requested_at' => $context->requestedAt->toIso8601String(),
                'dry_run' => $context->dryRun,
                'exception_class' => $e::class,
                'exception_message' => $e->getMessage(),
            ]);

            throw $e;
        } finally {
            optional($lock)->release();
        }
    }
}