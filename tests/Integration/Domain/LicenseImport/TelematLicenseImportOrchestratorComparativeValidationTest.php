<?php

declare(strict_types=1);

namespace Tests\Integration\Domain\LicenseImport;

use App\Domain\LicenseImport\DTO\LicenseImportExecutionContext;
use App\Domain\LicenseImport\Enums\BatchStatus;
use App\Domain\LicenseImport\Enums\IssueSeverity;
use App\Domain\LicenseImport\Enums\TriggerType;
use App\Domain\LicenseImport\Pipeline\TelematLicenseImportOrchestrator;
use App\Models\LicenseImportBatch;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

final class TelematLicenseImportOrchestratorComparativeValidationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpBaseLicenseImportConfig();
    }

    public function test_it_activates_the_batch_when_comparative_validation_only_emits_a_warning(): void
    {
        config()->set('license_import.validation.minimal.minimum_raw_rows_count', 1);
        config()->set('license_import.validation.comparative.enabled', true);
        config()->set('license_import.validation.comparative.total_rows_variation', [
            'warning_percent' => 40,
            'reject_percent' => 60,
        ]);
        config()->set('license_import.validation.comparative.valid_rows_variation', [
            'warning_percent' => 80,
            'reject_percent' => 90,
        ]);
        config()->set('license_import.validation.comparative.invalid_ratio_variation', [
            'warning_percent' => 80,
            'reject_percent' => 90,
        ]);
        config()->set('license_import.validation.comparative.field_fill_rate_variation', [
            'warning_percent' => 80,
            'reject_percent' => 90,
        ]);

        $previousBatch = $this->createActivePreviousBatchWithSummary(
            rawRowsCount: 2,
            validRowsCount: 2,
            invalidRowsCount: 0,
            requiredFieldFillRates: [
                'license_number' => 100,
                'last_name' => 100,
                'first_name' => 100,
            ],
        );

        $this->fakeSuccessfulHtmlResponse(
            $this->telematHtmlWithSingleValidRow()
        );

        $context = $this->makeExecutionContext(dryRun: false);

        /** @var TelematLicenseImportOrchestrator $orchestrator */
        $orchestrator = app(TelematLicenseImportOrchestrator::class);

        $orchestrator->run($context);

        /** @var LicenseImportBatch|null $newBatch */
        $newBatch = LicenseImportBatch::query()
            ->where('source', 'ffbi_telemat')
            ->latest('id')
            ->first();

        $this->assertNotNull($newBatch, 'Le nouveau batch devrait avoir été créé.');
        $this->assertNotSame($previousBatch->id, $newBatch->id, 'Le batch courant doit être distinct du batch précédent.');

        $this->assertSame('ffbi_telemat', $newBatch->source);
        $this->assertSame(BatchStatus::Activated, $newBatch->status);
        $this->assertTrue($newBatch->is_active, 'Le batch doit être activé malgré un warning comparatif.');
        $this->assertNotNull($newBatch->started_at);
        $this->assertNotNull($newBatch->activated_at);
        $this->assertNotNull($newBatch->finished_at);

        $this->assertSame(1, $newBatch->raw_rows_count);
        $this->assertSame(1, $newBatch->valid_rows_count);
        $this->assertSame(0, $newBatch->invalid_rows_count);
        $this->assertSame(0, $newBatch->error_count);
        $this->assertSame(1, $newBatch->warning_count);

        $snapshots = $newBatch->snapshots()->orderBy('row_index')->get();

        $this->assertCount(1, $snapshots, 'Le batch activé devrait contenir exactement 1 snapshot.');
        $this->assertTrue((bool) $snapshots[0]->is_valid);

        $issues = $newBatch->issues()->orderBy('id')->get();

        $this->assertCount(1, $issues, 'Le batch doit contenir uniquement le warning comparatif.');
        $this->assertSame(IssueSeverity::Warning, $issues[0]->severity);
        $this->assertSame('comparative_total_rows_variation_warning', $issues[0]->code);
        $this->assertNull($issues[0]->row_index);

        $issueContext = is_array($issues[0]->context) ? $issues[0]->context : [];
        $this->assertSame('total rows count', $issueContext['metric'] ?? null);
        $this->assertSame(1, $issueContext['current_value'] ?? null);
        $this->assertSame(2, $issueContext['previous_value'] ?? null);
        $this->assertSame(50, $issueContext['variation_percent'] ?? null);
        $this->assertSame(40, $issueContext['warning_percent'] ?? null);
        $this->assertSame(60, $issueContext['reject_percent'] ?? null);

        $this->assertNull(
            $newBatch->issues()->where('code', 'pipeline_unexpected_failure')->first(),
            'Aucune issue pipeline_unexpected_failure ne doit être créée pour un simple warning comparatif.'
        );

        $summary = is_array($newBatch->summary) ? $newBatch->summary : [];
        $comparativeSummary = is_array($summary['comparative_validation'] ?? null)
            ? $summary['comparative_validation']
            : [];

        $this->assertTrue($comparativeSummary['enabled'] ?? false);
        $this->assertFalse($comparativeSummary['skipped'] ?? true);
        $this->assertSame($previousBatch->id, $comparativeSummary['previous_batch_id'] ?? null);

        $currentSummary = is_array($comparativeSummary['current'] ?? null) ? $comparativeSummary['current'] : [];
        $previousSummary = is_array($comparativeSummary['previous'] ?? null) ? $comparativeSummary['previous'] : [];

        $this->assertSame(1, $currentSummary['raw_rows_count'] ?? null);
        $this->assertSame(1, $currentSummary['valid_rows_count'] ?? null);
        $this->assertSame(0, $currentSummary['invalid_rows_count'] ?? null);
        $this->assertSame(0, $currentSummary['invalid_ratio_percent'] ?? null);

        $this->assertSame(2, $previousSummary['raw_rows_count'] ?? null);
        $this->assertSame(2, $previousSummary['valid_rows_count'] ?? null);
        $this->assertSame(0, $previousSummary['invalid_rows_count'] ?? null);
        $this->assertSame(0, $previousSummary['invalid_ratio_percent'] ?? null);

        $previousBatch->refresh();

        $this->assertFalse(
            $previousBatch->is_active,
            'L’ancien batch actif doit être désactivé quand le nouveau batch est activé malgré un warning comparatif.'
        );

        $activeBatches = LicenseImportBatch::query()
            ->where('source', 'ffbi_telemat')
            ->where('is_active', true)
            ->get();

        $this->assertCount(1, $activeBatches, 'Il ne doit rester qu’un seul batch actif pour la source.');
        $this->assertSame($newBatch->id, $activeBatches->first()->id, 'Le batch actif restant doit être le nouveau batch.');
    }

    public function test_it_skips_comparative_validation_when_no_previous_active_batch_exists(): void
    {
        config()->set('license_import.validation.minimal.minimum_raw_rows_count', 1);
        config()->set('license_import.validation.comparative.enabled', true);
        config()->set('license_import.validation.comparative.total_rows_variation', [
            'warning_percent' => 25,
            'reject_percent' => 40,
        ]);
        config()->set('license_import.validation.comparative.valid_rows_variation', [
            'warning_percent' => 25,
            'reject_percent' => 40,
        ]);
        config()->set('license_import.validation.comparative.invalid_ratio_variation', [
            'warning_percent' => 25,
            'reject_percent' => 40,
        ]);
        config()->set('license_import.validation.comparative.field_fill_rate_variation', [
            'warning_percent' => 25,
            'reject_percent' => 40,
        ]);

        $this->fakeSuccessfulHtmlResponse(
            $this->telematHtmlWithSingleValidRow()
        );

        $context = $this->makeExecutionContext(dryRun: false);

        /** @var TelematLicenseImportOrchestrator $orchestrator */
        $orchestrator = app(TelematLicenseImportOrchestrator::class);

        $orchestrator->run($context);

        /** @var LicenseImportBatch|null $newBatch */
        $newBatch = LicenseImportBatch::query()
            ->where('source', 'ffbi_telemat')
            ->latest('id')
            ->first();

        $this->assertNotNull($newBatch, 'Le nouveau batch devrait avoir été créé.');

        $this->assertSame('ffbi_telemat', $newBatch->source);
        $this->assertSame(BatchStatus::Activated, $newBatch->status);
        $this->assertTrue($newBatch->is_active, 'Le batch doit être activé même sans batch précédent.');
        $this->assertNotNull($newBatch->started_at);
        $this->assertNotNull($newBatch->activated_at);
        $this->assertNotNull($newBatch->finished_at);

        $meta = is_array($newBatch->meta) ? $newBatch->meta : [];
        $executionMeta = is_array($meta['execution'] ?? null) ? $meta['execution'] : [];

        $this->assertTrue($executionMeta['finished'] ?? false);
        $this->assertSame('succeeded', $executionMeta['result'] ?? null);
        $this->assertSame('activated', $executionMeta['final_outcome'] ?? null);
        $this->assertNotNull($executionMeta['finished_at'] ?? null);

        $summary = is_array($newBatch->summary) ? $newBatch->summary : [];
        $executionSummary = is_array($summary['execution'] ?? null) ? $summary['execution'] : [];

        $this->assertTrue($executionSummary['finished'] ?? false);
        $this->assertSame('succeeded', $executionSummary['result'] ?? null);
        $this->assertSame('activated', $executionSummary['final_outcome'] ?? null);

        $this->assertSame(1, $newBatch->raw_rows_count);
        $this->assertSame(1, $newBatch->valid_rows_count);
        $this->assertSame(0, $newBatch->invalid_rows_count);
        $this->assertSame(0, $newBatch->error_count);
        $this->assertSame(0, $newBatch->warning_count);

        $snapshots = $newBatch->snapshots()->orderBy('row_index')->get();

        $this->assertCount(1, $snapshots, 'Le batch activé devrait contenir exactement 1 snapshot.');
        $this->assertTrue((bool) $snapshots[0]->is_valid);

        $issues = $newBatch->issues()->get();

        $this->assertCount(0, $issues, 'Aucune issue ne doit être créée quand la validation comparative est simplement skipped.');

        $comparativeSummary = is_array($summary['comparative_validation'] ?? null)
            ? $summary['comparative_validation']
            : [];

        $this->assertTrue($comparativeSummary['enabled'] ?? false);
        $this->assertTrue($comparativeSummary['skipped'] ?? false);
        $this->assertSame('no_previous_active_batch', $comparativeSummary['reason'] ?? null);

        $this->assertArrayNotHasKey(
            'previous_batch_id',
            $comparativeSummary,
            'Aucun previous_batch_id ne doit être renseigné quand aucun batch actif précédent n’existe.'
        );

        $activeBatches = LicenseImportBatch::query()
            ->where('source', 'ffbi_telemat')
            ->where('is_active', true)
            ->get();

        $this->assertCount(1, $activeBatches, 'Il ne doit rester qu’un seul batch actif pour la source.');
        $this->assertSame($newBatch->id, $activeBatches->first()->id, 'Le batch actif restant doit être le nouveau batch.');
    }

    public function test_it_activates_the_batch_when_comparative_validation_only_emits_a_field_fill_rate_warning(): void
    {
        config()->set('license_import.validation.minimal.minimum_raw_rows_count', 1);
        config()->set('license_import.validation.minimal.minimum_valid_ratio_percent', 0);
        config()->set('license_import.validation.minimal.maximum_invalid_ratio_percent', 100);
        config()->set('license_import.validation.minimal.required_field_fill_rate_percent', []);
        config()->set('license_import.validation.comparative.enabled', true);
        config()->set('license_import.validation.comparative.total_rows_variation', [
            'warning_percent' => 101,
            'reject_percent' => 101,
        ]);
        config()->set('license_import.validation.comparative.valid_rows_variation', [
            'warning_percent' => 101,
            'reject_percent' => 101,
        ]);
        config()->set('license_import.validation.comparative.invalid_ratio_variation', [
            'warning_percent' => 101,
            'reject_percent' => 101,
        ]);
        config()->set('license_import.validation.comparative.field_fill_rate_variation', [
            'warning_percent' => 25,
            'reject_percent' => 60,
        ]);

        $previousBatch = $this->createActivePreviousBatchWithSummary(
            rawRowsCount: 2,
            validRowsCount: 2,
            invalidRowsCount: 0,
            requiredFieldFillRates: [
                'license_number' => 100,
                'last_name' => 100,
                'first_name' => 100,
            ],
        );

        $this->fakeSuccessfulHtmlResponse(
            $this->telematHtmlWithMissingRequiredFirstNameOnSecondRow()
        );

        $context = $this->makeExecutionContext(dryRun: false);

        /** @var TelematLicenseImportOrchestrator $orchestrator */
        $orchestrator = app(TelematLicenseImportOrchestrator::class);

        $orchestrator->run($context);

        /** @var LicenseImportBatch|null $newBatch */
        $newBatch = LicenseImportBatch::query()
            ->where('source', 'ffbi_telemat')
            ->latest('id')
            ->first();

        $this->assertNotNull($newBatch, 'Le nouveau batch devrait avoir été créé.');
        $this->assertNotSame($previousBatch->id, $newBatch->id, 'Le batch courant doit être distinct du batch précédent.');

        $this->assertSame('ffbi_telemat', $newBatch->source);
        $this->assertSame(BatchStatus::Activated, $newBatch->status);
        $this->assertTrue($newBatch->is_active, 'Le batch doit être activé malgré les warnings.');
        $this->assertNotNull($newBatch->started_at);
        $this->assertNotNull($newBatch->activated_at);
        $this->assertNotNull($newBatch->finished_at);

        $meta = is_array($newBatch->meta) ? $newBatch->meta : [];
        $executionMeta = is_array($meta['execution'] ?? null) ? $meta['execution'] : [];

        $this->assertTrue($executionMeta['finished'] ?? false);
        $this->assertSame('succeeded', $executionMeta['result'] ?? null);
        $this->assertSame('activated', $executionMeta['final_outcome'] ?? null);
        $this->assertNotNull($executionMeta['finished_at'] ?? null);

        $summary = is_array($newBatch->summary) ? $newBatch->summary : [];
        $executionSummary = is_array($summary['execution'] ?? null) ? $summary['execution'] : [];

        $this->assertTrue($executionSummary['finished'] ?? false);
        $this->assertSame('succeeded', $executionSummary['result'] ?? null);
        $this->assertSame('activated', $executionSummary['final_outcome'] ?? null);

        $this->assertSame(2, $newBatch->raw_rows_count);
        $this->assertSame(1, $newBatch->valid_rows_count);
        $this->assertSame(1, $newBatch->invalid_rows_count);
        $this->assertSame(0, $newBatch->error_count);
        $this->assertSame(2, $newBatch->warning_count);

        $snapshots = $newBatch->snapshots()->orderBy('row_index')->get();

        $this->assertCount(2, $snapshots, 'Le batch activé devrait contenir exactement 2 snapshots.');
        $this->assertTrue((bool) $snapshots[0]->is_valid);
        $this->assertFalse((bool) $snapshots[1]->is_valid);

        $issues = $newBatch->issues()->orderBy('id')->get();

        $this->assertCount(2, $issues, 'Le batch doit contenir 2 warnings : missing_required_field et comparative_field_fill_rate_variation_first_name_warning.');

        $missingRequiredFieldIssue = $newBatch->issues()
            ->where('code', 'missing_required_field')
            ->first();

        $comparativeWarningIssue = $newBatch->issues()
            ->where('code', 'comparative_field_fill_rate_variation_first_name_warning')
            ->first();

        $this->assertNotNull($missingRequiredFieldIssue, 'Une issue missing_required_field doit être enregistrée.');
        $this->assertNotNull($comparativeWarningIssue, 'Une issue comparative_field_fill_rate_variation_first_name_warning doit être enregistrée.');

        $this->assertSame(IssueSeverity::Warning, $missingRequiredFieldIssue->severity);
        $this->assertSame(1, $missingRequiredFieldIssue->row_index);

        $this->assertSame(IssueSeverity::Warning, $comparativeWarningIssue->severity);
        $this->assertNull($comparativeWarningIssue->row_index);

        $comparativeContext = is_array($comparativeWarningIssue->context) ? $comparativeWarningIssue->context : [];
        $this->assertSame('field fill rate "first_name"', $comparativeContext['metric'] ?? null);
        $this->assertSame(50, $comparativeContext['current_value'] ?? null);
        $this->assertSame(100, $comparativeContext['previous_value'] ?? null);
        $this->assertSame(50, $comparativeContext['variation_percent'] ?? null);
        $this->assertSame(25, $comparativeContext['warning_percent'] ?? null);
        $this->assertSame(60, $comparativeContext['reject_percent'] ?? null);

        $this->assertNull(
            $newBatch->issues()->where('code', 'pipeline_unexpected_failure')->first(),
            'Aucune issue pipeline_unexpected_failure ne doit être créée pour un simple warning comparatif.'
        );

        $minimalValidationSummary = is_array($summary['minimal_validation'] ?? null)
            ? $summary['minimal_validation']
            : [];
        $comparativeSummary = is_array($summary['comparative_validation'] ?? null)
            ? $summary['comparative_validation']
            : [];

        $requiredFieldFillRates = is_array($minimalValidationSummary['required_field_fill_rates'] ?? null)
            ? $minimalValidationSummary['required_field_fill_rates']
            : [];

        $this->assertSame(50, $requiredFieldFillRates['first_name'] ?? null);

        $this->assertTrue($comparativeSummary['enabled'] ?? false);
        $this->assertFalse($comparativeSummary['skipped'] ?? true);
        $this->assertSame($previousBatch->id, $comparativeSummary['previous_batch_id'] ?? null);

        $currentSummary = is_array($comparativeSummary['current'] ?? null) ? $comparativeSummary['current'] : [];
        $previousSummary = is_array($comparativeSummary['previous'] ?? null) ? $comparativeSummary['previous'] : [];

        $currentFillRates = is_array($currentSummary['required_field_fill_rates'] ?? null)
            ? $currentSummary['required_field_fill_rates']
            : [];
        $previousFillRates = is_array($previousSummary['required_field_fill_rates'] ?? null)
            ? $previousSummary['required_field_fill_rates']
            : [];

        $this->assertSame(50, $currentFillRates['first_name'] ?? null);
        $this->assertSame(100, $previousFillRates['first_name'] ?? null);

        $previousBatch->refresh();

        $this->assertFalse(
            $previousBatch->is_active,
            'L’ancien batch actif doit être désactivé quand le nouveau batch est activé malgré un warning comparatif.'
        );

        $activeBatches = LicenseImportBatch::query()
            ->where('source', 'ffbi_telemat')
            ->where('is_active', true)
            ->get();

        $this->assertCount(1, $activeBatches, 'Il ne doit rester qu’un seul batch actif pour la source.');
        $this->assertSame($newBatch->id, $activeBatches->first()->id, 'Le batch actif restant doit être le nouveau batch.');
    }

    private function telematHtmlWithMissingRequiredFirstNameOnSecondRow(): string
    {
        return <<<'HTML'
    <html>
    <body>
        <table id="licenses">
        <thead>
            <tr>
            <th>Num&eacute;ro</th>
            <th>Nom</th>
            <th>Pr&eacute;nom</th>
            <th>Cat&eacute;gorie</th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
            </tr>
        </thead>
        <tbody>
            <tr>
            <td><a href="./?cs=token-1">191100 S</a></td>
            <td><a href="./?cs=token-1">ARROUAS</a></td>
            <td><a href="./?cs=token-1">ISABELLE</a></td>
            <td class="c"><a href="./?cs=token-1">Decouverte</a></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td><a href="./?cs=token-2">190399 F</a></td>
            <td><a href="./?cs=token-2">AUCHART</a></td>
            <td>&nbsp;</td>
            <td class="c"><a href="./?cs=token-2">Decouverte</a></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
        </tbody>
        </table>
    </body>
    </html>
    HTML;
    }

    private function setUpBaseLicenseImportConfig(): void
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

    private function makeExecutionContext(bool $dryRun = false): LicenseImportExecutionContext
    {
        return new LicenseImportExecutionContext(
            source: 'ffbi_telemat',
            triggerType: TriggerType::Manual,
            triggeredByUserId: null,
            triggeredByLabel: 'phpunit-comparative-validation-test',
            requestedAt: CarbonImmutable::now(),
            dryRun: $dryRun,
        );
    }

    private function fakeSuccessfulHtmlResponse(string $html): void
    {
        Http::fake([
            '*' => Http::response(
                $html,
                200,
                ['Content-Type' => 'text/html; charset=UTF-8']
            ),
        ]);
    }

    private function telematHtmlWithSingleValidRow(): string
    {
        return <<<'HTML'
<html>
<body>
    <table id="licenses">
    <thead>
        <tr>
        <th>Num&eacute;ro</th>
        <th>Nom</th>
        <th>Pr&eacute;nom</th>
        <th>Cat&eacute;gorie</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        <tr>
        <td><a href="./?cs=token-1">191100 S</a></td>
        <td><a href="./?cs=token-1">ARROUAS</a></td>
        <td><a href="./?cs=token-1">ISABELLE</a></td>
        <td class="c"><a href="./?cs=token-1">Decouverte</a></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        </tr>
    </tbody>
    </table>
</body>
</html>
HTML;
    }

    private function createActivePreviousBatchWithSummary(
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
}