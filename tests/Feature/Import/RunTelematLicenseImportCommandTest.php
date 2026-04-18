<?php

declare(strict_types=1);

namespace Tests\Feature\Import;

use App\Jobs\RunTelematLicenseImportJob;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

final class RunTelematLicenseImportCommandTest extends TestCase
{
    public function test_it_dispatches_license_import_job_with_default_options(): void
    {
        Queue::fake();

        config()->set('license_import.job.queue', 'imports');

        $this->artisan('license-import:run')
            ->expectsOutput('License import job dispatched successfully.')
            ->expectsOutput('Source: ffbi_telemat | Dry-run: no | Label: artisan-cli')
            ->assertSuccessful();

        Queue::assertPushedOn('imports', RunTelematLicenseImportJob::class, function (RunTelematLicenseImportJob $job): bool {
            $payload = $this->extractJobPayload($job);

            $this->assertSame('ffbi_telemat', $payload['source'] ?? null);
            $this->assertSame('manual', $payload['trigger_type'] ?? null);
            $this->assertNull($payload['triggered_by_user_id'] ?? null);
            $this->assertSame('artisan-cli', $payload['triggered_by_label'] ?? null);
            $this->assertFalse($payload['dry_run'] ?? true);
            $this->assertIsString($payload['requested_at'] ?? null);
            $this->assertNotSame('', $payload['requested_at'] ?? '');

            return true;
        });
    }

    public function test_it_dispatches_license_import_job_with_explicit_options(): void
    {
        Queue::fake();

        config()->set('license_import.job.queue', 'license-imports');

        $this->artisan('license-import:run', [
            '--source' => 'custom_telemat_source',
            '--dry-run' => true,
            '--triggered-by-label' => 'manual-admin-run',
        ])
            ->expectsOutput('License import job dispatched successfully.')
            ->expectsOutput('Source: custom_telemat_source | Dry-run: yes | Label: manual-admin-run')
            ->assertSuccessful();

        Queue::assertPushedOn('license-imports', RunTelematLicenseImportJob::class, function (RunTelematLicenseImportJob $job): bool {
            $payload = $this->extractJobPayload($job);

            $this->assertSame('custom_telemat_source', $payload['source'] ?? null);
            $this->assertSame('manual', $payload['trigger_type'] ?? null);
            $this->assertNull($payload['triggered_by_user_id'] ?? null);
            $this->assertSame('manual-admin-run', $payload['triggered_by_label'] ?? null);
            $this->assertTrue($payload['dry_run'] ?? false);
            $this->assertIsString($payload['requested_at'] ?? null);
            $this->assertNotSame('', $payload['requested_at'] ?? '');

            return true;
        });
    }

    /**
     * @return array<string, mixed>
     */
    private function extractJobPayload(RunTelematLicenseImportJob $job): array
    {
        $this->assertIsArray($job->payload);

        return $job->payload;
    }
}