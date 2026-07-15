<?php

namespace App\Admin\Controllers;

use App\Domain\LicenseImport\DTO\LicenseImportExecutionContext;
use App\Domain\LicenseImport\Enums\BatchStatus;
use App\Domain\LicenseImport\Enums\TriggerType;
use App\Jobs\RunTelematLicenseImportJob;
use App\Models\LicenseImportBatch;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Facades\Admin;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;

/**
 * Back-office des imports de licences (pipeline Telemat / FFBI).
 *
 * Ecran en lecture seule : les batches sont crees par le pipeline
 * (commande license-import:run ou bouton "Lancer un import" ci-dessous).
 * On ne peut donc ni creer, ni editer, ni supprimer un batch depuis l'admin.
 */
class LicenseImportBatchAdminController extends AdminController
{
    protected $title = 'Imports de licences';

    /**
     * Libelles + couleurs des statuts de batch.
     */
    private const STATUS_STYLES = [
        'pending'                 => ['Analyse en attente', 'default'],
        'running'                 => ['En cours', 'info'],
        'fetched'                 => ['Recupere', 'info'],
        'parsed'                  => ['Analyse', 'info'],
        'validated_minimal'       => ['Validation minimale OK', 'info'],
        'validated_comparative'   => ['Validation comparative OK', 'info'],
        'activated'               => ['Active', 'success'],
        'rejected'                => ['Rejete', 'warning'],
        'failed'                  => ['Echec', 'danger'],
    ];

    protected function grid()
    {
        $grid = new Grid(new LicenseImportBatch());

        $grid->model()->orderByDesc('started_at');

        // Helpers captures en closures liees : insensibles au rebind de scope
        // qu'open-admin applique aux closures d'affichage.
        $statusBadge = \Closure::fromCallable([self::class, 'statusBadge']);

        $grid->column('id', 'ID')->sortable();
        $grid->column('source', 'Source');
        $grid->column('status', 'Statut')->display(function ($status) use ($statusBadge) {
            return $statusBadge($status);
        });
        $grid->column('is_active', 'Actif')->display(function ($active) {
            return $active
                ? '<span class="badge badge-success">actif</span>'
                : '<span class="badge badge-default">-</span>';
        });
        $grid->column('raw_rows_count', 'Lignes')->sortable();
        $grid->column('valid_rows_count', 'Valides')->sortable();
        $grid->column('invalid_rows_count', 'Invalides')->sortable();
        $grid->column('error_count', 'Err.')->display(function ($n) {
            return $n > 0 ? '<span class="badge badge-danger">' . $n . '</span>' : '0';
        });
        $grid->column('warning_count', 'Warn.')->display(function ($n) {
            return $n > 0 ? '<span class="badge badge-warning">' . $n . '</span>' : '0';
        });
        $grid->column('trigger_type', 'Declenche par')->display(function ($type) {
            $value = $type instanceof TriggerType ? $type->value : (string) $type;
            $label = $this->triggered_by_label ? ' (' . e($this->triggered_by_label) . ')' : '';
            return e($value) . $label;
        });
        $grid->column('started_at', 'Demarre')->display(function ($v) {
            return $v ? \Illuminate\Support\Carbon::parse($v)->format('d/m/Y H:i') : '-';
        })->sortable();
        $grid->column('finished_at', 'Termine')->display(function ($v) {
            return $v ? \Illuminate\Support\Carbon::parse($v)->format('d/m/Y H:i') : '-';
        });

        // Filtres
        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
            $filter->equal('source', 'Source');
            $filter->equal('status', 'Statut')->select(array_map(
                fn ($style) => $style[0],
                self::STATUS_STYLES
            ));
            $filter->equal('is_active', 'Actif')->select([0 => 'Non', 1 => 'Oui']);
        });

        // Lecture seule
        $grid->disableCreateButton();
        $grid->actions(function ($actions) {
            $actions->disableEdit();
            $actions->disableDelete();
        });

        // Boutons de declenchement d'import
        $grid->tools(function ($tools) {
            $dryUrl  = admin_url('license-import/run?dry_run=1');
            $realUrl = admin_url('license-import/run');

            $tools->append(
                '<a href="' . $dryUrl . '" class="btn btn-sm btn-default" '
                . 'title="Simule l\'import sans activer le batch">'
                . '<i class="icon-flask"></i> Tester (dry-run)</a>'
            );
            $tools->append(
                '<a href="' . $realUrl . '" class="btn btn-sm btn-primary" '
                . 'onclick="return confirm(\'Lancer un import reel des licences ? '
                . 'Le batch pourra devenir actif et remplacer les licencies.\');">'
                . '<i class="icon-sync"></i> Lancer l\'import</a>'
            );
        });

        return $grid;
    }

    protected function detail($id)
    {
        $show = new Show(LicenseImportBatch::findOrFail($id));

        // Helpers captures : les closures ->as() sont rebindees sur le modele.
        $statusBadge = \Closure::fromCallable([self::class, 'statusBadge']);
        $renderIssues = \Closure::fromCallable([self::class, 'renderIssues']);
        $jsonBlock = \Closure::fromCallable([self::class, 'jsonBlock']);

        $show->field('id', 'ID');
        $show->field('source', 'Source');
        $show->field('status', 'Statut')->unescape()->as(function ($status) use ($statusBadge) {
            return $statusBadge($status);
        });
        $show->field('is_active', 'Actif')->as(fn ($v) => $v ? 'Oui' : 'Non');
        $show->field('trigger_type', 'Type de declenchement')->as(function ($type) {
            return $type instanceof TriggerType ? $type->value : (string) $type;
        });
        $show->field('triggered_by_label', 'Declenche par (label)');
        $show->field('raw_rows_count', 'Lignes brutes');
        $show->field('valid_rows_count', 'Lignes valides');
        $show->field('invalid_rows_count', 'Lignes invalides');
        $show->field('error_count', 'Erreurs');
        $show->field('warning_count', 'Warnings');
        $show->field('started_at', 'Demarre le');
        $show->field('activated_at', 'Active le');
        $show->field('finished_at', 'Termine le');

        $show->field('issues_html', 'Issues')->unescape()->as(function () use ($renderIssues) {
            return $renderIssues($this->issues);
        });

        $show->field('summary', 'Summary (execution / projection)')->unescape()->as(function ($summary) use ($jsonBlock) {
            return $jsonBlock($summary);
        });
        $show->field('meta', 'Meta')->unescape()->as(function ($meta) use ($jsonBlock) {
            return $jsonBlock($meta);
        });

        $show->panel()->tools(function ($tools) {
            $tools->disableEdit();
            $tools->disableDelete();
        });

        return $show;
    }

    /**
     * Declenche un import de licences depuis l'admin.
     *
     * La queue est en mode "sync" : le job s'execute immediatement dans la
     * requete. On capture donc le resultat (ou l'echec) du pipeline pour le
     * remonter a l'utilisateur.
     */
    public function run(Request $request)
    {
        $dryRun = $request->boolean('dry_run');
        $user   = Admin::user();

        $context = new LicenseImportExecutionContext(
            source: 'ffbi_telemat',
            triggerType: TriggerType::Manual,
            triggeredByUserId: $user?->id,
            triggeredByLabel: 'admin:' . ($user->username ?? $user->name ?? 'inconnu'),
            requestedAt: CarbonImmutable::now(),
            dryRun: $dryRun,
        );

        try {
            RunTelematLicenseImportJob::dispatch($context->toArray())
                ->onQueue(config('license_import.job.queue', 'default'));

            $batch = LicenseImportBatch::latestFirst()->first();
            $statusValue = $batch?->status instanceof BatchStatus
                ? $batch->status->value
                : (string) ($batch?->status ?? 'inconnu');

            admin_success(
                $dryRun ? 'Dry-run termine' : 'Import termine',
                sprintf(
                    'Batch #%s - statut : %s - %d ligne(s), %d valide(s), %d invalide(s).',
                    $batch?->id ?? '?',
                    $statusValue,
                    $batch?->raw_rows_count ?? 0,
                    $batch?->valid_rows_count ?? 0,
                    $batch?->invalid_rows_count ?? 0,
                )
            );
        } catch (\Throwable $e) {
            admin_error(
                'Echec de l\'import',
                'Le pipeline a echoue : ' . $e->getMessage() . ' (voir le dernier batch pour le detail).'
            );
        }

        return redirect(admin_url('license-import/batches'));
    }

    private static function statusBadge($status): string
    {
        $value = $status instanceof BatchStatus ? $status->value : (string) $status;
        [$label, $color] = self::STATUS_STYLES[$value] ?? [$value, 'default'];

        return '<span class="badge badge-' . $color . '">' . e($label) . '</span>';
    }

    private static function renderIssues($issues): string
    {
        if ($issues === null || $issues->isEmpty()) {
            return '<em>Aucune issue.</em>';
        }

        $rows = '';
        foreach ($issues as $issue) {
            $severity = $issue->severity->value ?? (string) $issue->severity;
            $color = match ($severity) {
                'error', 'critical' => 'danger',
                'warning'           => 'warning',
                default             => 'info',
            };
            $rows .= '<tr>'
                . '<td><span class="badge badge-' . $color . '">' . e($severity) . '</span></td>'
                . '<td><code>' . e($issue->code) . '</code></td>'
                . '<td>' . e($issue->message) . '</td>'
                . '<td>' . e((string) ($issue->row_index ?? '-')) . '</td>'
                . '</tr>';
        }

        return '<table class="table table-sm table-bordered">'
            . '<thead><tr><th>Severite</th><th>Code</th><th>Message</th><th>Ligne</th></tr></thead>'
            . '<tbody>' . $rows . '</tbody></table>';
    }

    private static function jsonBlock($value): string
    {
        if (empty($value)) {
            return '<em>-</em>';
        }

        $json = json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        return '<pre style="max-height:400px;overflow:auto;">' . e($json) . '</pre>';
    }
}
