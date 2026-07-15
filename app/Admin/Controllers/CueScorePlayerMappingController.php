<?php

namespace App\Admin\Controllers;

use App\Jobs\RunCueScoreImportJob;
use App\Models\CueScorePlayerMapping;
use App\Models\Licencies;
use Illuminate\Http\Request;
use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;

/**
 * Revue des correspondances joueur CueScore -> licencie du club.
 *
 * Les mappings sont crees automatiquement par le matcher lors de l'import
 * (cuescore:import --with-match). Cet ecran permet de :
 * - relire les correspondances (surtout celles a faible score, non confirmees),
 * - reassocier manuellement un participant a un licencie,
 * - confirmer ou dissocier une correspondance,
 * - relancer un import + matching CueScore par discipline.
 */
class CueScorePlayerMappingController extends AdminController
{
    protected $title = 'Correspondances CueScore';

    /** Disciplines disposant de classements CueScore. */
    private const DISCIPLINES = ['blackball', 'americain', 'snooker'];

    protected function grid()
    {
        $grid = new Grid(new CueScorePlayerMapping());

        // Les correspondances non confirmees et a faible score remontent en premier.
        $grid->model()
            ->orderBy('is_confirmed')
            ->orderByDesc('confidence_score');

        $scoreBadge = \Closure::fromCallable([self::class, 'scoreBadge']);

        $grid->column('id', 'ID')->sortable();
        $grid->column('cuescore_name', 'Joueur CueScore');
        $grid->column('licencie.nom', 'Licencie associe')->display(function () {
            $l = $this->licencie;
            if (!$l) {
                return '<span class="badge badge-default">non associe</span>';
            }
            return e(trim(($l->nom ?? '') . ' ' . ($l->prenom ?? ''))) . ' <small>(' . e($l->licence ?? '') . ')</small>';
        });
        $grid->column('confidence_score', 'Score')->display(function ($score) use ($scoreBadge) {
            return $scoreBadge($score);
        })->sortable();
        $grid->column('matching_method', 'Methode');
        $grid->column('is_confirmed', 'Confirme')->display(function ($confirmed) {
            return $confirmed
                ? '<span class="badge badge-success">confirme</span>'
                : '<span class="badge badge-warning">a verifier</span>';
        });

        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
            $filter->like('cuescore_name', 'Joueur CueScore');
            $filter->equal('is_confirmed', 'Confirme')->select([0 => 'Non', 1 => 'Oui']);
            $filter->equal('matching_method', 'Methode')->select([
                'exact_normalized' => 'exact_normalized',
                'similarity'       => 'similarity',
                'unmatched'        => 'unmatched',
            ]);
            $filter->where(function ($query) {
                $query->whereNull('licencie_id');
            }, 'Non associes', 'unlinked');
        });

        $grid->disableCreateButton();

        // Boutons de declenchement d'import + matching CueScore (par discipline).
        $grid->tools(function ($tools) {
            foreach (self::DISCIPLINES as $discipline) {
                $url = admin_url('cuescore-mappings/run?discipline=' . $discipline);
                $tools->append(
                    '<a href="' . $url . '" class="btn btn-sm btn-default" '
                    . 'onclick="return confirm(\'Importer et re-matcher les classements ' . $discipline . ' ? '
                    . 'Cette operation interroge l\\\'API CueScore et peut prendre un moment.\');">'
                    . '<i class="icon-sync"></i> Importer ' . ucfirst($discipline) . '</a>'
                );
            }
            $urlAll = admin_url('cuescore-mappings/run');
            $tools->append(
                '<a href="' . $urlAll . '" class="btn btn-sm btn-primary" '
                . 'onclick="return confirm(\'Importer et re-matcher TOUS les classements actifs ? '
                . 'Operation potentiellement longue (appels API CueScore).\');">'
                . '<i class="icon-sync"></i> Importer tout + matcher</a>'
            );
        });

        return $grid;
    }

    protected function detail($id)
    {
        $show = new Show(CueScorePlayerMapping::findOrFail($id));

        $scoreBadge = \Closure::fromCallable([self::class, 'scoreBadge']);

        $show->field('id', 'ID');
        $show->field('cuescore_participant_id', 'ID participant CueScore');
        $show->field('cuescore_name', 'Joueur CueScore');
        $show->field('cuescore_url', 'URL CueScore')->unescape()->as(function ($url) {
            return $url ? '<a href="' . e($url) . '" target="_blank">' . e($url) . '</a>' : '-';
        });
        $show->field('licencie', 'Licencie associe')->unescape()->as(function ($licencie) {
            if (!$licencie) {
                return '<em>non associe</em>';
            }
            return e(trim(($licencie->nom ?? '') . ' ' . ($licencie->prenom ?? '')) . ' (' . ($licencie->licence ?? '') . ')');
        });
        $show->field('confidence_score', 'Score')->unescape()->as(function ($score) use ($scoreBadge) {
            return $scoreBadge($score);
        });
        $show->field('matching_method', 'Methode');
        $show->field('is_confirmed', 'Confirme')->as(fn ($v) => $v ? 'Oui' : 'Non');
        $show->field('notes', 'Notes');

        return $show;
    }

    protected function form()
    {
        $form = new Form(new CueScorePlayerMapping());

        $options = Licencies::orderBy('nom')->orderBy('prenom')->get()
            ->mapWithKeys(function ($l) {
                $label = trim(($l->nom ?? '') . ' ' . ($l->prenom ?? ''));
                if ($l->licence) {
                    $label .= ' — ' . $l->licence;
                }
                return [$l->id => $label];
            })
            ->toArray();

        $form->display('cuescore_name', 'Joueur CueScore');
        $form->display('confidence_score', 'Score de correspondance');
        $form->display('matching_method', 'Methode de correspondance');

        $form->select('licencie_id', 'Licencie associe')
            ->options($options)
            ->help('Laisser vide pour marquer la correspondance comme non associee.');

        $form->switch('is_confirmed', 'Correspondance confirmee');
        $form->textarea('notes', 'Notes');

        // Champs geres par le matcher : non editables ici.
        $form->disableEditingCheck();

        return $form;
    }

    /**
     * Declenche un import CueScore + matching depuis l'admin.
     *
     * Queue en mode sync : la commande s'execute inline. On borne la duree en
     * ciblant une discipline (ou tous les classements actifs).
     */
    public function run(Request $request)
    {
        $discipline = $request->query('discipline');
        $discipline = ($discipline && in_array($discipline, self::DISCIPLINES, true)) ? $discipline : null;

        $connection = config('queue.admin_import_connection', 'database');
        $suffix = $discipline ? ' (' . $discipline . ')' : '';

        try {
            RunCueScoreImportJob::dispatch($discipline)->onConnection($connection);

            if ($connection === 'sync') {
                admin_success('Import CueScore termine' . $suffix, 'Import et matching executes.');
            } else {
                admin_success(
                    'Import CueScore lance' . $suffix,
                    'Traitement en arriere-plan (un worker « php artisan queue:work » doit tourner).'
                );
            }
        } catch (\Throwable $e) {
            admin_error('Echec de l\'import CueScore', $e->getMessage());
        }

        return redirect(admin_url('cuescore-mappings'));
    }

    private static function scoreBadge($score): string
    {
        if ($score === null) {
            return '<span class="badge badge-default">-</span>';
        }

        $score = (int) $score;
        $color = match (true) {
            $score >= 95 => 'success',
            $score >= 85 => 'info',
            $score > 0   => 'warning',
            default      => 'default',
        };

        return '<span class="badge badge-' . $color . '">' . $score . '</span>';
    }
}
