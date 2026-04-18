<?php

declare(strict_types=1);

namespace Tests\Integration\Domain\LicenseImport;

use App\Domain\LicenseImport\DTO\LicenseImportExecutionContext;
use App\Domain\LicenseImport\Enums\BatchStatus;
use App\Domain\LicenseImport\Enums\IssueSeverity;
use App\Domain\LicenseImport\Enums\TriggerType;
use App\Domain\LicenseImport\Fetch\Contracts\TelematFetcherInterface;
use App\Models\LicenseImportBatch;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

abstract class TelematLicenseImportOrchestratorTestCase extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpBaseLicenseImportConfig();
    }

    protected function setUpBaseLicenseImportConfig(): void
    {
        config()->set('license_import.source.name', 'ffbi_telemat');
        config()->set('license_import.source.base_url', 'https://example.test');
        config()->set('license_import.source.endpoint', '/licenses');
        config()->set('license_import.source.method', 'POST');
        config()->set('license_import.source.form.fields', [
            'dummy' => 'value',
        ]);
        config()->set('license_import.source.auth.username', 'test-user');
        config()->set('license_import.source.auth.password', 'test-password');

        config()->set('license_import.http.timeout', 10);
        config()->set('license_import.http.connect_timeout', 5);
        config()->set('license_import.http.retries.times', 0);
        config()->set('license_import.http.retries.sleep_ms', 0);
        config()->set('license_import.http.user_agent', 'PHPUnit License Import Test');
        config()->set('license_import.http.headers', [
            'Accept' => 'text/html,application/xhtml+xml',
        ]);
        config()->set('license_import.http.allow_redirects', true);

        config()->set('license_import.parsing.table_selector', '#licenses');
        config()->set('license_import.parsing.use_header_detection_fallback', false);
        config()->set('license_import.parsing.minimum_detected_rows', 1);
        config()->set('license_import.parsing.header_normalization.trim', true);
        config()->set('license_import.parsing.header_normalization.lowercase', true);
        config()->set('license_import.parsing.header_normalization.collapse_spaces', true);
        config()->set('license_import.parsing.header_normalization.strip_accents', true);
        config()->set('license_import.parsing.header_normalization.strip_punctuation', true);

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
            'category' => [
                'sources' => ['categorie'],
            ],
        ]);

        config()->set('license_import.mapping.source_columns', [
            'source_license_number' => [
                'sources' => ['numero'],
            ],
            'source_category_label' => [
                'sources' => ['categorie'],
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

        config()->set('license_import.observability.log_channel', 'stack');
        config()->set('license_import.observability.store_source_columns', true);
        config()->set('license_import.observability.store_source_fingerprint', true);
        config()->set('license_import.observability.store_raw_html_excerpt', false);
        config()->set('license_import.observability.raw_html_excerpt_max_length', 1000);
    }

    protected function makeExecutionContext(
        bool $dryRun = false,
        string $triggeredByLabel = 'phpunit-license-import-test',
    ): LicenseImportExecutionContext {
        return new LicenseImportExecutionContext(
            source: 'ffbi_telemat',
            triggerType: TriggerType::Manual,
            triggeredByUserId: null,
            triggeredByLabel: $triggeredByLabel,
            requestedAt: CarbonImmutable::now(),
            dryRun: $dryRun,
        );
    }

    protected function fakeSuccessfulHtmlResponse(string $html): void
    {
        Http::fake([
            '*' => Http::response(
                $html,
                200,
                ['Content-Type' => 'text/html; charset=UTF-8']
            ),
        ]);
    }

    protected function createActivePreviousBatch(): LicenseImportBatch
    {
        return $this->createPreviousBatch(
            rawRowsCount: 2,
            validRowsCount: 2,
            invalidRowsCount: 0,
            requiredFieldFillRates: null,
            triggeredByLabel: 'previous-active-batch',
            sourceFingerprint: 'previous-batch-fingerprint',
            status: BatchStatus::Activated,
            isActive: true,
        );
    }

    protected function createActivePreviousBatchWithSummary(
        int $rawRowsCount,
        int $validRowsCount,
        int $invalidRowsCount,
        array $requiredFieldFillRates,
    ): LicenseImportBatch {
        return $this->createPreviousBatch(
            rawRowsCount: $rawRowsCount,
            validRowsCount: $validRowsCount,
            invalidRowsCount: $invalidRowsCount,
            requiredFieldFillRates: $requiredFieldFillRates,
            triggeredByLabel: 'previous-active-batch-with-summary',
            sourceFingerprint: 'previous-batch-fingerprint-with-summary',
            status: BatchStatus::Activated,
            isActive: true,
        );
    }

    protected function createInactivePreviousBatchWithSummary(
        int $rawRowsCount,
        int $validRowsCount,
        int $invalidRowsCount,
        array $requiredFieldFillRates,
    ): LicenseImportBatch {
        return $this->createPreviousBatch(
            rawRowsCount: $rawRowsCount,
            validRowsCount: $validRowsCount,
            invalidRowsCount: $invalidRowsCount,
            requiredFieldFillRates: $requiredFieldFillRates,
            triggeredByLabel: 'previous-inactive-batch-with-summary',
            sourceFingerprint: 'previous-inactive-batch-fingerprint',
            status: BatchStatus::ValidatedComparative,
            isActive: false,
        );
    }

    protected function swapTelematFetcher(TelematFetcherInterface $fetcher): void
    {
        $this->app->instance(TelematFetcherInterface::class, $fetcher);
    }

    protected function assertBatchFailed(
        LicenseImportBatch $batch,
        string $expectedFailureStage,
        string $expectedFailureCode,
        ?string $expectedFailureMessage = null,
    ): void {
        $batch->refresh();

        $this->assertSame(BatchStatus::Failed, $batch->status);
        $this->assertFalse($batch->is_active);
        $this->assertNull($batch->activated_at);
        $this->assertNotNull($batch->started_at);
        $this->assertNotNull($batch->finished_at);

        $meta = is_array($batch->meta) ? $batch->meta : [];
        $failureMeta = is_array($meta['failure'] ?? null) ? $meta['failure'] : [];
        $executionMeta = is_array($meta['execution'] ?? null) ? $meta['execution'] : [];

        $this->assertSame($expectedFailureStage, $failureMeta['failure_stage'] ?? null);
        $this->assertSame($expectedFailureCode, $failureMeta['failure_code'] ?? null);
        $this->assertIsString($failureMeta['failure_message'] ?? null);
        $this->assertNotSame('', trim((string) ($failureMeta['failure_message'] ?? '')));

        if ($expectedFailureMessage !== null) {
            $this->assertSame($expectedFailureMessage, $failureMeta['failure_message'] ?? null);
        }

        $this->assertTrue((bool) ($executionMeta['finished'] ?? false));
        $this->assertSame('failed', $executionMeta['result'] ?? null);
        $this->assertSame('failed', $executionMeta['final_outcome'] ?? null);
        $this->assertNotNull($executionMeta['finished_at'] ?? null);

        $summary = is_array($batch->summary) ? $batch->summary : [];
        $executionSummary = is_array($summary['execution'] ?? null) ? $summary['execution'] : [];

        $this->assertTrue((bool) ($executionSummary['finished'] ?? false));
        $this->assertSame('failed', $executionSummary['result'] ?? null);
        $this->assertSame('failed', $executionSummary['final_outcome'] ?? null);
        $this->assertSame($expectedFailureStage, $executionSummary['failure_stage'] ?? null);
        $this->assertSame($expectedFailureCode, $executionSummary['failure_code'] ?? null);
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

        $this->assertTrue((bool) ($executionMeta['finished'] ?? false));
        $this->assertSame('failed', $executionMeta['result'] ?? null);
        $this->assertSame('failed', $executionMeta['final_outcome'] ?? null);
        $this->assertNotNull($executionMeta['finished_at'] ?? null);

        $summary = is_array($batch->summary) ? $batch->summary : [];
        $executionSummary = is_array($summary['execution'] ?? null) ? $summary['execution'] : [];

        $this->assertTrue((bool) ($executionSummary['finished'] ?? false));
        $this->assertSame('failed', $executionSummary['result'] ?? null);
        $this->assertSame('failed', $executionSummary['final_outcome'] ?? null);
    }

    protected function assertIssueCountersAreConsistent(LicenseImportBatch $batch): void
    {
        $batch->refresh();

        $errorCount = $batch->issues()->where('severity', IssueSeverity::Error)->count();
        $warningCount = $batch->issues()->where('severity', IssueSeverity::Warning)->count();

        $this->assertSame($errorCount, $batch->error_count);
        $this->assertSame($warningCount, $batch->warning_count);

        $summary = is_array($batch->summary) ? $batch->summary : [];
        $issueCounters = is_array($summary['issue_counters'] ?? null) ? $summary['issue_counters'] : [];

        $this->assertSame($errorCount, $issueCounters['error_count'] ?? null);
        $this->assertSame($warningCount, $issueCounters['warning_count'] ?? null);
    }

    protected function assertPreviousBatchRemainsActive(
        LicenseImportBatch $previousBatch,
        string $source = 'ffbi_telemat',
    ): void {
        $previousBatch->refresh();

        $this->assertTrue($previousBatch->is_active);

        $activeBatches = LicenseImportBatch::query()
            ->where('source', $source)
            ->where('is_active', true)
            ->get();

        $this->assertCount(1, $activeBatches);
        $this->assertSame($previousBatch->id, $activeBatches->first()->id);
    }

    protected function assertBatchActivated(LicenseImportBatch $batch): void
    {
        $batch->refresh();

        $this->assertSame(BatchStatus::Activated, $batch->status);
        $this->assertTrue($batch->is_active);
        $this->assertNotNull($batch->activated_at);
        $this->assertNotNull($batch->started_at);
        $this->assertNotNull($batch->finished_at);

        $meta = is_array($batch->meta) ? $batch->meta : [];
        $executionMeta = is_array($meta['execution'] ?? null) ? $meta['execution'] : [];

        $this->assertTrue((bool) ($executionMeta['finished'] ?? false));
        $this->assertSame('succeeded', $executionMeta['result'] ?? null);
        $this->assertSame('activated', $executionMeta['final_outcome'] ?? null);
        $this->assertNotNull($executionMeta['finished_at'] ?? null);

        $summary = is_array($batch->summary) ? $batch->summary : [];
        $executionSummary = is_array($summary['execution'] ?? null) ? $summary['execution'] : [];

        $this->assertTrue((bool) ($executionSummary['finished'] ?? false));
        $this->assertSame('succeeded', $executionSummary['result'] ?? null);
        $this->assertSame('activated', $executionSummary['final_outcome'] ?? null);
    }

    protected function assertBatchCounts(
        LicenseImportBatch $batch,
        int $rawRowsCount,
        int $validRowsCount,
        int $invalidRowsCount,
    ): void {
        $batch->refresh();

        $this->assertSame($rawRowsCount, $batch->raw_rows_count);
        $this->assertSame($validRowsCount, $batch->valid_rows_count);
        $this->assertSame($invalidRowsCount, $batch->invalid_rows_count);
    }

    protected function assertOnlyOneActiveBatch(): void
    {
        $this->assertSame(
            1,
            LicenseImportBatch::query()
                ->where('source', 'ffbi_telemat')
                ->where('is_active', true)
                ->count(),
        );
    }

    private function createPreviousBatch(
        int $rawRowsCount,
        int $validRowsCount,
        int $invalidRowsCount,
        ?array $requiredFieldFillRates,
        string $triggeredByLabel,
        string $sourceFingerprint,
        BatchStatus $status,
        bool $isActive,
    ): LicenseImportBatch {
        return LicenseImportBatch::query()->create([
            'source' => 'ffbi_telemat',
            'status' => $status,
            'is_active' => $isActive,
            'activated_at' => $isActive ? now()->subDay() : null,
            'started_at' => now()->subDay(),
            'finished_at' => now()->subDay(),
            'trigger_type' => TriggerType::Manual,
            'triggered_by_user_id' => null,
            'triggered_by_label' => $triggeredByLabel,
            'raw_rows_count' => $rawRowsCount,
            'valid_rows_count' => $validRowsCount,
            'invalid_rows_count' => $invalidRowsCount,
            'error_count' => 0,
            'warning_count' => 0,
            'source_fingerprint' => $sourceFingerprint,
            'source_columns' => ['Numéro', 'Nom', 'Prénom', 'Catégorie'],
            'meta' => [],
            'summary' => $this->makePreviousBatchSummary(
                rawRowsCount: $rawRowsCount,
                validRowsCount: $validRowsCount,
                invalidRowsCount: $invalidRowsCount,
                requiredFieldFillRates: $requiredFieldFillRates,
            ),
        ]);
    }

    private function makePreviousBatchSummary(
        int $rawRowsCount,
        int $validRowsCount,
        int $invalidRowsCount,
        ?array $requiredFieldFillRates,
    ): array {
        if ($requiredFieldFillRates === null) {
            return [];
        }

        return [
            'minimal_validation' => [
                'raw_rows_count' => $rawRowsCount,
                'valid_rows_count' => $validRowsCount,
                'invalid_rows_count' => $invalidRowsCount,
                'valid_ratio_percent' => $rawRowsCount > 0
                    ? (int) floor(($validRowsCount / $rawRowsCount) * 100)
                    : 0,
                'invalid_ratio_percent' => $rawRowsCount > 0
                    ? (int) floor(($invalidRowsCount / $rawRowsCount) * 100)
                    : 0,
                'required_field_fill_rates' => $requiredFieldFillRates,
                'duplicate_license_numbers' => [],
            ],
        ];
    }
}