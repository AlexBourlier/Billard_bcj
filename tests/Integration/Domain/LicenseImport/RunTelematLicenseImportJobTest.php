<?php

declare(strict_types=1);

namespace Tests\Integration\Domain\LicenseImport;

use App\Domain\LicenseImport\Enums\BatchStatus;
use App\Domain\LicenseImport\Fetch\Exceptions\TelematInvalidResponseException;
use App\Jobs\RunTelematLicenseImportJob;
use App\Models\LicenseImportBatch;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\Cache\Lock;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

final class RunTelematLicenseImportJobTest extends TelematLicenseImportOrchestratorTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config()->set('license_import.mapping.required_fields', [
            'license_number',
            'last_name',
            'first_name',
        ]);

        config()->set('license_import.mapping.fields', [
            'license_number' => [
                'sources' => ['num ero'],
            ],
            'last_name' => [
                'sources' => ['nom'],
            ],
            'first_name' => [
                'sources' => ['pr enom'],
            ],
            'category' => [
                'sources' => ['cat egorie'],
            ],
        ]);

        config()->set('license_import.validation.minimal.minimum_raw_rows_count', 1);
        config()->set('license_import.validation.minimal.minimum_valid_ratio_percent', 100);
        config()->set('license_import.validation.minimal.maximum_invalid_ratio_percent', 0);
        config()->set('license_import.validation.minimal.required_field_fill_rate_percent', [
            'license_number' => 100,
            'last_name' => 100,
            'first_name' => 100,
        ]);
        config()->set('license_import.validation.minimal.duplicate_license_number_policy', 'reject');

        config()->set('license_import.validation.comparative.enabled', false);

        config()->set('license_import.activation.auto_activate_when_valid', true);
        config()->set('license_import.activation.dry_run', false);
    }

    public function test_it_calls_orchestrator_when_lock_is_acquired(): void
    {
        $payload = [
            'source' => 'ffbi_telemat',
            'trigger_type' => 'manual',
            'triggered_by_user_id' => null,
            'triggered_by_label' => 'job-test',
            'requested_at' => CarbonImmutable::now()->toIso8601String(),
            'dry_run' => true,
        ];

        $lock = \Mockery::mock(Lock::class);
        $lock->shouldReceive('get')->once()->andReturn(true);
        $lock->shouldReceive('release')->once();

        Cache::shouldReceive('lock')
            ->once()
            ->with(
                config('license_import.lock.name', 'license-import:ffbi-telemat'),
                config('license_import.lock.ttl_seconds', 900),
            )
            ->andReturn($lock);

        $this->fakeSuccessfulHtmlResponse($this->validTelematHtml());

        $job = new RunTelematLicenseImportJob($payload);

        $job->handle();

        $batch = LicenseImportBatch::query()
            ->where('source', 'ffbi_telemat')
            ->latest('id')
            ->first();

        $this->assertNotNull($batch);
        $this->assertFalse($batch->is_active);
        $this->assertNull($batch->activated_at);
        $this->assertNotNull($batch->finished_at);
        $this->assertSame(1, $batch->raw_rows_count);
        $this->assertSame(1, $batch->valid_rows_count);
        $this->assertSame(0, $batch->invalid_rows_count);

        $summary = is_array($batch->summary) ? $batch->summary : [];
        $executionSummary = is_array($summary['execution'] ?? null) ? $summary['execution'] : [];
        $activationSummary = is_array($summary['activation'] ?? null) ? $summary['activation'] : [];

        $this->assertSame('succeeded', $executionSummary['result'] ?? null);
        $this->assertSame('dry_run', $executionSummary['final_outcome'] ?? null);
        $this->assertFalse($activationSummary['attempted'] ?? true);
        $this->assertFalse($activationSummary['activated'] ?? true);
        $this->assertSame('dry_run', $activationSummary['reason'] ?? null);
    }

    public function test_it_skips_orchestrator_when_lock_cannot_be_acquired(): void
    {
        $payload = [
            'source' => 'ffbi_telemat',
            'trigger_type' => 'manual',
            'triggered_by_user_id' => null,
            'triggered_by_label' => 'job-test',
            'requested_at' => CarbonImmutable::now()->toIso8601String(),
            'dry_run' => false,
        ];

        $lock = \Mockery::mock(Lock::class);
        $lock->shouldReceive('get')->once()->andReturn(false);
        $lock->shouldNotReceive('release');

        Cache::shouldReceive('lock')
            ->once()
            ->andReturn($lock);

        $job = new RunTelematLicenseImportJob($payload);

        $job->handle();

        $this->assertDatabaseCount('license_import_batches', 0);
    }

    public function test_it_rethrows_exception_when_orchestrator_fails(): void
    {
        $payload = [
            'source' => 'ffbi_telemat',
            'trigger_type' => 'manual',
            'triggered_by_user_id' => null,
            'triggered_by_label' => 'job-test',
            'requested_at' => CarbonImmutable::now()->toIso8601String(),
            'dry_run' => false,
        ];

        $lock = \Mockery::mock(Lock::class);
        $lock->shouldReceive('get')->once()->andReturn(true);
        $lock->shouldReceive('release')->once();

        Cache::shouldReceive('lock')
            ->once()
            ->andReturn($lock);

        Http::fake([
            '*' => Http::response(
                '{"ok":false}',
                200,
                ['Content-Type' => 'application/json']
            ),
        ]);

        $job = new RunTelematLicenseImportJob($payload);

        $this->expectException(TelematInvalidResponseException::class);
        $this->expectExceptionMessage('Telemat response Content-Type [application/json] is not in the expected list.');

        try {
            $job->handle();
        } finally {
            $batch = LicenseImportBatch::query()
                ->where('source', 'ffbi_telemat')
                ->latest('id')
                ->first();

            $this->assertNotNull($batch);
            $this->assertSame(BatchStatus::Failed, $batch->status);
            $this->assertFalse($batch->is_active);
            $this->assertNotNull($batch->finished_at);
        }
    }

    private function validTelematHtml(): string
    {
        return <<<'HTML'
<html>
<body>
    <table id="licenses">
        <thead>
            <tr>
                <th>Num Ero</th>
                <th>Nom</th>
                <th>Pr Enom</th>
                <th>Cat Egorie</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>LIC-001</td>
                <td>Dupont</td>
                <td>Jean</td>
                <td>Senior</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
HTML;
    }

    protected function tearDown(): void
    {
        \Mockery::close();

        parent::tearDown();
    }
}