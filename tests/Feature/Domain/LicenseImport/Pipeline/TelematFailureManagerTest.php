<?php

declare(strict_types=1);

namespace Tests\Feature\Domain\LicenseImport\Pipeline;

use App\Domain\LicenseImport\Enums\BatchStatus;
use App\Domain\LicenseImport\Enums\IssueSeverity;
use App\Domain\LicenseImport\Enums\TriggerType;
use App\Domain\LicenseImport\Fetch\Exceptions\TelematFetchException;
use App\Domain\LicenseImport\Fetch\Exceptions\TelematInvalidResponseException;
use App\Domain\LicenseImport\Fetch\Exceptions\TelematNetworkException;
use App\Domain\LicenseImport\Pipeline\TelematFailureManager;
use App\Models\LicenseImportBatch;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use RuntimeException;
use Tests\Support\CreatesLicenseImportExecutionContext;
use Tests\TestCase;

final class TelematFailureManagerTest extends TestCase
{
    use RefreshDatabase;
    use CreatesLicenseImportExecutionContext;

    public function test_it_handles_stage_failure_and_marks_batch_as_failed(): void
    {
        $batch = $this->createBatch([
            'status' => 'running',
            'is_active' => true,
            'meta' => ['existing' => ['meta' => true]],
            'summary' => ['existing' => ['summary' => true]],
        ]);

        $context = $this->makeLicenseImportExecutionContext(
            source: 'telemat',
            triggerType: TriggerType::Manual,
            dryRun: false,
        );

        $exception = new RuntimeException('Parser crashed');

        /** @var TelematFailureManager $manager */
        $manager = app(TelematFailureManager::class);

        $manager->handleStageFailure(
            $batch,
            $context,
            $exception,
            'parsing',
            'parsing_failed',
        );

        $batch = $batch->fresh();

        self::assertNotNull($batch);
        self::assertSame(BatchStatus::Failed, $batch->status);
        self::assertFalse((bool) $batch->is_active);
        self::assertNotNull($batch->finished_at);

        self::assertSame('failed', data_get($batch->meta, 'execution.result'));
        self::assertSame('failed', data_get($batch->meta, 'execution.final_outcome'));
        self::assertSame('parsing', data_get($batch->meta, 'failure.failure_stage'));
        self::assertSame('parsing_failed', data_get($batch->meta, 'failure.failure_code'));
        self::assertSame('Parser crashed', data_get($batch->meta, 'failure.failure_message'));

        self::assertSame('failed', data_get($batch->summary, 'execution.result'));
        self::assertSame('failed', data_get($batch->summary, 'execution.final_outcome'));
        self::assertSame('parsing', data_get($batch->summary, 'execution.failure_stage'));
        self::assertSame('parsing_failed', data_get($batch->summary, 'execution.failure_code'));

        self::assertTrue((bool) data_get($batch->meta, 'existing.meta'));
        self::assertTrue((bool) data_get($batch->summary, 'existing.summary'));

        $issue = $batch->issues()->latest('id')->first();

        self::assertNotNull($issue);
        self::assertSame(IssueSeverity::Error, $issue->severity);
        self::assertSame('parsing_failed', $issue->code);
        self::assertSame('Parser crashed', $issue->message);

        $issueContext = is_array($issue->context) ? $issue->context : [];
        self::assertSame(RuntimeException::class, $issueContext['exception_class'] ?? null);
        self::assertSame('telemat', $issueContext['source'] ?? null);
        self::assertSame(TriggerType::Manual->value, $issueContext['trigger_type'] ?? null);
        self::assertSame('parsing', $issueContext['failure_stage'] ?? null);

        $this->assertIssueCountersAreConsistent($batch);
    }

    public function test_it_handles_unexpected_failure_and_marks_batch_as_failed(): void
    {
        $batch = $this->createBatch([
            'status' => 'running',
            'is_active' => true,
        ]);

        $context = $this->makeLicenseImportExecutionContext(
            source: 'telemat',
            triggerType: TriggerType::Manual,
            dryRun: false,
        );

        $exception = new RuntimeException('Unexpected boom');

        /** @var TelematFailureManager $manager */
        $manager = app(TelematFailureManager::class);

        $manager->handleUnexpectedFailure($batch, $context, $exception);

        $batch = $batch->fresh();

        self::assertNotNull($batch);
        self::assertSame(BatchStatus::Failed, $batch->status);
        self::assertFalse((bool) $batch->is_active);
        self::assertNotNull($batch->finished_at);

        self::assertSame('pipeline', data_get($batch->meta, 'failure.failure_stage'));
        self::assertSame('pipeline_unexpected_failure', data_get($batch->meta, 'failure.failure_code'));
        self::assertSame('Unexpected boom', data_get($batch->meta, 'failure.failure_message'));

        self::assertSame('pipeline', data_get($batch->summary, 'execution.failure_stage'));
        self::assertSame('pipeline_unexpected_failure', data_get($batch->summary, 'execution.failure_code'));

        $issue = $batch->issues()->latest('id')->first();

        self::assertNotNull($issue);
        self::assertSame(IssueSeverity::Error, $issue->severity);
        self::assertSame('pipeline_unexpected_failure', $issue->code);
        self::assertSame('Unexpected boom', $issue->message);

        $issueContext = is_array($issue->context) ? $issue->context : [];
        self::assertSame(RuntimeException::class, $issueContext['exception_class'] ?? null);
        self::assertSame('telemat', $issueContext['source'] ?? null);

        $this->assertIssueCountersAreConsistent($batch);
    }

    public function test_it_handles_generic_fetch_failure_and_marks_batch_as_failed(): void
    {
        $batch = $this->createBatch([
            'status' => 'running',
            'is_active' => true,
        ]);

        $context = $this->makeLicenseImportExecutionContext(
            source: 'telemat',
            triggerType: TriggerType::Manual,
            dryRun: false,
        );

        $exception = new TelematFetchException('Fetch failed');

        /** @var TelematFailureManager $manager */
        $manager = app(TelematFailureManager::class);

        $manager->handleFetchFailure($batch, $context, $exception);

        $batch = $batch->fresh();

        self::assertNotNull($batch);
        self::assertSame(BatchStatus::Failed, $batch->status);
        self::assertFalse((bool) $batch->is_active);
        self::assertSame('fetch', data_get($batch->meta, 'failure.failure_stage'));
        self::assertSame('fetch_failed', data_get($batch->meta, 'failure.failure_code'));
        self::assertSame('Fetch failed', data_get($batch->meta, 'failure.failure_message'));

        $issue = $batch->issues()->latest('id')->first();

        self::assertNotNull($issue);
        self::assertSame(IssueSeverity::Error, $issue->severity);
        self::assertSame('fetch_failed', $issue->code);
        self::assertSame('Fetch failed', $issue->message);

        $issueContext = is_array($issue->context) ? $issue->context : [];
        self::assertSame(TelematFetchException::class, $issueContext['exception_class'] ?? null);
        self::assertSame('telemat', $issueContext['source'] ?? null);

        $this->assertIssueCountersAreConsistent($batch);
    }

    public function test_it_handles_network_fetch_failure_with_network_issue_code(): void
    {
        $batch = $this->createBatch([
            'status' => 'running',
            'is_active' => true,
        ]);

        $context = $this->makeLicenseImportExecutionContext(
            source: 'telemat',
            triggerType: TriggerType::Manual,
            dryRun: false,
        );

        $exception = new TelematNetworkException('Connection timeout');

        /** @var TelematFailureManager $manager */
        $manager = app(TelematFailureManager::class);

        $manager->handleFetchFailure($batch, $context, $exception);

        $batch = $batch->fresh();

        self::assertNotNull($batch);
        self::assertSame(BatchStatus::Failed, $batch->status);
        self::assertFalse((bool) $batch->is_active);
        self::assertSame('fetch', data_get($batch->meta, 'failure.failure_stage'));
        self::assertSame('fetch_network_failure', data_get($batch->meta, 'failure.failure_code'));
        self::assertSame('Connection timeout', data_get($batch->meta, 'failure.failure_message'));

        $issue = $batch->issues()->latest('id')->first();

        self::assertNotNull($issue);
        self::assertSame(IssueSeverity::Error, $issue->severity);
        self::assertSame('fetch_network_failure', $issue->code);
        self::assertSame('Connection timeout', $issue->message);

        $issueContext = is_array($issue->context) ? $issue->context : [];
        self::assertSame(TelematNetworkException::class, $issueContext['exception_class'] ?? null);
        self::assertSame('telemat', $issueContext['source'] ?? null);

        $this->assertIssueCountersAreConsistent($batch);
    }

    public function test_it_handles_invalid_response_fetch_failure_with_reason_and_response_context(): void
    {
        $batch = $this->createBatch([
            'status' => 'running',
            'is_active' => true,
        ]);

        $context = $this->makeLicenseImportExecutionContext(
            source: 'telemat',
            triggerType: TriggerType::Manual,
            dryRun: false,
        );

        $exception = new TelematInvalidResponseException(
            message: 'Invalid HTML structure',
            reason: 'missing_table',
            context: ['selector' => 'table.results'],
        );

        /** @var TelematFailureManager $manager */
        $manager = app(TelematFailureManager::class);

        $manager->handleFetchFailure($batch, $context, $exception);

        $batch = $batch->fresh();

        self::assertNotNull($batch);
        self::assertSame(BatchStatus::Failed, $batch->status);
        self::assertFalse((bool) $batch->is_active);
        self::assertSame('fetch', data_get($batch->meta, 'failure.failure_stage'));
        self::assertSame('fetch_invalid_response', data_get($batch->meta, 'failure.failure_code'));
        self::assertSame('Invalid HTML structure', data_get($batch->meta, 'failure.failure_message'));

        $issue = $batch->issues()->latest('id')->first();

        self::assertNotNull($issue);
        self::assertSame(IssueSeverity::Error, $issue->severity);
        self::assertSame('fetch_invalid_response', $issue->code);
        self::assertSame('Invalid HTML structure', $issue->message);

        $issueContext = is_array($issue->context) ? $issue->context : [];
        self::assertSame(TelematInvalidResponseException::class, $issueContext['exception_class'] ?? null);
        self::assertSame('telemat', $issueContext['source'] ?? null);
        self::assertSame('missing_table', $issueContext['reason'] ?? null);
        self::assertSame(['selector' => 'table.results'], $issueContext['response_context'] ?? null);

        $this->assertIssueCountersAreConsistent($batch);
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

    private function assertIssueCountersAreConsistent(LicenseImportBatch $batch): void
    {
        $batch->refresh();

        $errorCount = $batch->issues()
            ->where('severity', IssueSeverity::Error)
            ->count();

        $warningCount = $batch->issues()
            ->where('severity', IssueSeverity::Warning)
            ->count();

        self::assertSame($errorCount, $batch->error_count);
        self::assertSame($warningCount, $batch->warning_count);
    }
}