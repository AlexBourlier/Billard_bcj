<?php

namespace App\Console\Commands;

use App\Domain\LicenseImport\DTO\LicenseImportExecutionContext;
use App\Domain\LicenseImport\Enums\TriggerType;
use App\Jobs\RunTelematLicenseImportJob;
use Carbon\CarbonImmutable;
use Illuminate\Console\Command;

class RunTelematLicenseImportCommand extends Command
{
    protected $signature = 'license-import:run
                            {--source=ffbi_telemat : Logical source name}
                            {--dry-run : Execute import without activating the batch}
                            {--triggered-by-label= : Custom label for audit/logging}';

    protected $description = 'Dispatch the Telemat/FFBI license import job';

    public function handle(): int
    {
        $context = new LicenseImportExecutionContext(
            source: (string) $this->option('source'),
            triggerType: TriggerType::Manual,
            triggeredByUserId: null,
            triggeredByLabel: $this->option('triggered-by-label') ?: 'artisan-cli',
            requestedAt: CarbonImmutable::now(),
            dryRun: (bool) $this->option('dry-run'),
        );

        RunTelematLicenseImportJob::dispatch($context->toArray())
            ->onQueue(config('license_import.job.queue', 'default'));

        $this->info('License import job dispatched successfully.');
        $this->line(sprintf(
            'Source: %s | Dry-run: %s | Label: %s',
            $context->source,
            $context->dryRun ? 'yes' : 'no',
            $context->triggeredByLabel ?? 'n/a'
        ));

        return self::SUCCESS;
    }
}