<?php

namespace App\Admin\Controllers;

use App\Models\CueScoreRanking;
use Illuminate\Support\Str;
use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;

/**
 * CRUD des classements CueScore (table cuescore_rankings).
 *
 * Ecran param-driven : la discipline et la portee (scope) sont passees en
 * query string (?discipline=blackball&scope=national). Une entree de menu par
 * combo discipline+portee pointe vers cet ecran deja filtre. La creation et
 * l'edition conservent ce contexte via des champs caches.
 *
 * C'est la source unique lue par le pipeline (cuescore:import) et l'API front.
 */
class CueScoreRankingController extends AdminController
{
    /** Categories disponibles (individuel). Les equipes utilisent team_category. */
    private const CATEGORIES = [
        'Mixte'          => 'Mixte',
        'Féminin'        => 'Féminin',
        'Junior (U18)'   => 'Junior (U18)',
        'Espoir (U23)'   => 'Espoir (U23)',
        'Benjamin (U15)' => 'Benjamin (U15)',
        'Vétéran'        => 'Vétéran',
        'Master'         => 'Master',
        'Handi fauteuil' => 'Handi fauteuil',
        'Handi debout'   => 'Handi debout',
        'Top ligue'      => 'Top ligue',
        'Équipes'        => 'Équipes',
    ];

    protected function title()
    {
        $discipline = request('discipline');
        $scope = request('scope');

        if ($discipline || $scope) {
            return 'Classements · ' . Str::ucfirst((string) $discipline)
                . ($scope ? ' · ' . Str::ucfirst((string) $scope) : '');
        }

        return 'Classements CueScore';
    }

    protected function grid()
    {
        $discipline = request('discipline');
        $scope = request('scope');

        $grid = new Grid(new CueScoreRanking());

        if ($discipline) {
            $grid->model()->where('discipline', $discipline);
        }
        if ($scope) {
            $grid->model()->where('scope', $scope);
        }
        $grid->model()->orderBy('sort_order');

        $grid->column('category', 'Catégorie');
        $grid->column('ranking_type', 'Type')->display(function ($type) {
            return $type === 'team'
                ? '<span class="badge badge-info">équipe</span>'
                : '<span class="badge badge-default">individuel</span>';
        });
        $grid->column('team_category', 'Cat. équipe');
        $grid->column('name', 'Nom');
        $grid->column('cuescore_id', 'ID CueScore')->display(function ($id) {
            return $id ? '<code>' . e($id) . '</code>' : '-';
        });
        $grid->column('url', 'Lien')->display(function ($url) {
            return $url ? '<a href="' . e($url) . '" target="_blank"><i class="icon-external-link"></i></a>' : '-';
        });
        $grid->column('season', 'Saison');
        $grid->column('is_active', 'Actif')->display(function ($active) {
            return $active
                ? '<span class="badge badge-success">actif</span>'
                : '<span class="badge badge-default">inactif</span>';
        });
        $grid->column('sort_order', 'Ordre')->sortable();

        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
            $filter->equal('category', 'Catégorie')->select(self::CATEGORIES);
            $filter->equal('ranking_type', 'Type')->select([
                'individual' => 'Individuel',
                'team'       => 'Équipe',
            ]);
            $filter->like('name', 'Nom');
        });

        // Bouton "Nouveau" qui conserve la discipline + la portee courantes.
        $grid->disableCreateButton();
        $grid->tools(function ($tools) use ($discipline, $scope) {
            $url = admin_url('cuescore-classements/create')
                . '?discipline=' . urlencode((string) $discipline)
                . '&scope=' . urlencode((string) $scope);
            $tools->append(
                '<a href="' . $url . '" class="btn btn-sm btn-success">'
                . '<i class="icon-plus"></i> Nouveau</a>'
            );
        });

        return $grid;
    }

    protected function form()
    {
        $form = new Form(new CueScoreRanking());

        // Contexte discipline + portee : rempli depuis la query (creation) ou
        // depuis le modele (edition, ou default() est ignore).
        $form->hidden('discipline')->default(request('discipline'));
        $form->hidden('scope')->default(request('scope'));

        $form->text('name', 'Nom')->required();
        $form->text('cuescore_id', 'ID CueScore')
            ->rules('required')
            ->help('Identifiant du classement cote CueScore (ex : 66292051).');
        $form->url('url', 'URL CueScore');

        $form->select('source_type', 'Source')->options([
            'ranking'    => 'Classement (ranking)',
            'tournament' => 'Tournoi (tournament)',
        ])->default('ranking')->required();

        $form->select('ranking_type', 'Type')->options([
            'individual' => 'Individuel',
            'team'       => 'Équipe',
        ])->default('individual')->required();

        $form->select('category', 'Catégorie')
            ->options(self::CATEGORIES)
            ->help('Mixte, Féminin, Junior… (laisser vide si non applicable).');

        $form->text('team_category', 'Catégorie équipe')
            ->help('Uniquement pour les classements par équipe : DN1, DN2, DR1…');

        $form->text('season', 'Saison')->default('2025-2026');
        $form->switch('is_active', 'Actif')->default(true);
        $form->number('sort_order', 'Ordre d\'affichage')->default(0);

        return $form;
    }

    protected function detail($id)
    {
        $show = new Show(CueScoreRanking::findOrFail($id));

        $show->field('id', 'ID');
        $show->field('discipline', 'Discipline');
        $show->field('scope', 'Portée');
        $show->field('category', 'Catégorie');
        $show->field('ranking_type', 'Type');
        $show->field('team_category', 'Catégorie équipe');
        $show->field('name', 'Nom');
        $show->field('cuescore_id', 'ID CueScore');
        $show->field('url', 'URL CueScore')->unescape()->as(function ($url) {
            return $url ? '<a href="' . e($url) . '" target="_blank">' . e($url) . '</a>' : '-';
        });
        $show->field('source_type', 'Source');
        $show->field('season', 'Saison');
        $show->field('is_active', 'Actif')->as(fn ($v) => $v ? 'Oui' : 'Non');
        $show->field('sort_order', 'Ordre');

        return $show;
    }
}
