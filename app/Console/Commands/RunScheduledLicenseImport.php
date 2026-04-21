<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Domain\LicenseImport\Reporting\TelematBatchReportBuilder;
use App\Models\LicenseImportBatch;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

final class RunScheduledLicenseImport extends Command
{
    protected $signature = 'license-import:scheduled-run';
    protected $description = 'Run the license import and write a TXT report on disk';

    public function __construct(
        private readonly TelematBatchReportBuilder $reportBuilder,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $startedAt = now();

        $this->info('Starting scheduled license import...');

        $exitCode = Artisan::call('license-import:run');
        $commandOutput = Artisan::output();

        $batch = LicenseImportBatch::query()->latest('id')->first();

        $reportPath = storage_path('app/license-import');
        File::ensureDirectoryExists($reportPath);

        $timestamp = now()->format('Y-m-d_H-i-s');
        $file = $reportPath . "/license-import-report-{$timestamp}.txt";

        $content = [];
        $content[] = 'LICENSE IMPORT REPORT';
        $content[] = '=====================';
        $content[] = '';
        $content[] = 'Started at: ' . $startedAt->toDateTimeString();
        $content[] = 'Finished at: ' . now()->toDateTimeString();
        $content[] = 'Command exit code: ' . $exitCode;
        $content[] = '';

        if ($batch === null) {
            $content[] = 'No batch found after command execution.';
            $content[] = '';
            $content[] = 'Raw command output:';
            $content[] = $commandOutput;

            File::put($file, implode(PHP_EOL, $content));

            $this->error("No batch found. TXT report written to: {$file}");

            return self::FAILURE;
        }

        $report = $this->reportBuilder->build($batch);

        $projection = $report['projection'] ?? [];
        $validation = $report['validation'] ?? [];
        $result = $report['result'] ?? [];
        $issues = $report['issues'] ?? [];
        $execution = $report['execution'] ?? [];
        $batchData = $report['batch'] ?? [];

        $content[] = 'Batch';
        $content[] = '-----';
        $content[] = 'Batch ID: ' . ($batchData['id'] ?? 'n/a');
        $content[] = 'Source: ' . ($batchData['source'] ?? 'n/a');
        $content[] = 'Status: ' . ($batchData['status'] ?? 'n/a');
        $content[] = 'Active: ' . $this->boolToText($batchData['is_active'] ?? false);
        $content[] = '';

        $content[] = 'Execution';
        $content[] = '---------';
        $content[] = 'Result: ' . ($execution['result'] ?? 'n/a');
        $content[] = 'Final outcome: ' . ($execution['final_outcome'] ?? 'n/a');
        $content[] = '';

        $content[] = 'Validation';
        $content[] = '----------';
        $content[] = 'Raw rows: ' . ($validation['raw_rows_count'] ?? 0);
        $content[] = 'Valid rows: ' . ($validation['valid_rows_count'] ?? 0);
        $content[] = 'Invalid rows: ' . ($validation['invalid_rows_count'] ?? 0);
        $content[] = 'Error count: ' . ($validation['error_count'] ?? 0);
        $content[] = 'Warning count: ' . ($validation['warning_count'] ?? 0);
        $content[] = '';

        $content[] = 'Projection';
        $content[] = '----------';
        $content[] = 'Executed: ' . $this->boolToText($projection['executed'] ?? false);
        $content[] = 'Failed: ' . $this->boolToText($projection['failed'] ?? false);
        $content[] = 'Strategy: ' . ($projection['strategy'] ?? 'n/a');
        $content[] = 'Reason: ' . ($projection['reason'] ?? 'n/a');
        $content[] = 'Inserted: ' . ($projection['inserted_count'] ?? 0);
        $content[] = 'Updated: ' . ($projection['updated_count'] ?? 0);
        $content[] = 'Deleted: ' . ($projection['deleted_count'] ?? 0);
        $content[] = 'Unchanged: ' . ($projection['unchanged_count'] ?? 0);
        $content[] = 'No-op: ' . $this->boolToText($projection['no_op'] ?? false);
        $content[] = '';

        $content[] = 'Database update status';
        $content[] = '----------------------';
        $dbWasUpdated =
            (($projection['inserted_count'] ?? 0) > 0)
            || (($projection['updated_count'] ?? 0) > 0)
            || (($projection['deleted_count'] ?? 0) > 0);

        $content[] = 'Database changed: ' . $this->boolToText($dbWasUpdated);
        $content[] = '';

        $content[] = 'Issues';
        $content[] = '------';

        $issueItems = $issues['items'] ?? [];

        if ($issueItems === []) {
            $content[] = 'No issues reported.';
        } else {
            foreach ($issueItems as $index => $issue) {
                $content[] = sprintf(
                    '%d. [%s] %s',
                    $index + 1,
                    $issue['level'] ?? 'unknown',
                    $issue['message'] ?? 'No message'
                );
            }
        }

        $content[] = '';
        $content[] = 'Command output';
        $content[] = '--------------';
        $content[] = trim($commandOutput) !== '' ? trim($commandOutput) : 'No console output.';
        $content[] = '';

        File::put($file, implode(PHP_EOL, $content));

        $this->info("TXT report written to: {$file}");

        return $exitCode === 0 ? self::SUCCESS : self::FAILURE;
    }

    private function boolToText(bool $value): string
    {
        return $value ? 'yes' : 'no';
    }
}