<?php

namespace App\Admin\Controllers;

use App\Models\InfoBlock;
use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;

/**
 * Blocs d'information importants affiches sur la page d'accueil (fermeture,
 * horaires, inscriptions, tournoi, info urgente...).
 */
class InfoBlockController extends AdminController
{
    protected $title = 'Informations page d\'accueil';

    private const NIVEAUX = [
        'info'      => 'Information',
        'important' => 'Important',
        'urgent'    => 'Urgent',
    ];

    private const NIVEAU_COLORS = [
        'info'      => 'bg-info',
        'important' => 'bg-warning',
        'urgent'    => 'bg-danger',
    ];

    protected function grid()
    {
        $grid = new Grid(new InfoBlock());

        $grid->model()->orderBy('ordre')->orderByDesc('id');

        $grid->column('ordre', __('Ordre'))->sortable();
        $grid->column('titre', __('Titre'));
        $grid->column('niveau', __('Niveau'))->display(function ($niveau) {
            $color = self::NIVEAU_COLORS[$niveau] ?? 'bg-secondary';
            $label = self::NIVEAUX[$niveau] ?? $niveau;
            return "<span class='badge {$color}'>{$label}</span>";
        });
        $grid->column('actif', __('Actif'))->display(function ($v) {
            return $v
                ? '<span class="badge bg-success">actif</span>'
                : '<span class="badge bg-secondary">inactif</span>';
        });
        $grid->column('date_fin', __('Fin'));

        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
            $filter->like('titre', __('Titre'));
            $filter->equal('niveau', __('Niveau'))->select(self::NIVEAUX);
            $filter->equal('actif', __('Actif'))->select([0 => 'Non', 1 => 'Oui']);
        });

        return $grid;
    }

    protected function detail($id)
    {
        $show = new Show(InfoBlock::findOrFail($id));

        $show->field('titre', __('Titre'));
        $show->field('resume', __('Message'));
        $show->field('niveau', __('Niveau'))->as(fn ($n) => self::NIVEAUX[$n] ?? $n);
        $show->field('lien', __('Lien'));
        $show->field('date_debut', __('Debut'));
        $show->field('date_fin', __('Fin'));
        $show->field('actif', __('Actif'))->as(fn ($v) => $v ? 'Oui' : 'Non');
        $show->field('ordre', __('Ordre'));

        return $show;
    }

    protected function form()
    {
        $form = new Form(new InfoBlock());

        $form->text('titre', __('Titre'))->required()
            ->help('Titre court affiche en tete du bloc (ex : « Fermeture exceptionnelle »).');
        $form->textarea('resume', __('Message'))
            ->help('Texte du message affiche aux visiteurs.');
        $form->select('niveau', __('Niveau d\'importance'))->options(self::NIVEAUX)->default('info')
            ->help('Information (bleu), Important (orange) ou Urgent (rouge) : change la couleur du bloc.');
        $form->url('lien', __('Lien (facultatif)'))
            ->help('Si renseigne, un bouton « En savoir plus » renverra vers cette adresse.');
        $form->date('date_debut', __('Date de debut (facultatif)'))
            ->help('Avant cette date, le bloc n\'est pas affiche.');
        $form->date('date_fin', __('Date de fin (facultatif)'))
            ->help('Apres cette date, le bloc n\'est plus affiche (mais reste enregistre).');
        $form->number('ordre', __('Ordre d\'affichage'))->default(0)
            ->help('Si plusieurs blocs sont actifs, le plus petit nombre s\'affiche en premier.');
        $form->switch('actif', __('Actif'))->default(true)
            ->help('Desactiver masque le bloc du site sans le supprimer.');

        return $form;
    }
}
