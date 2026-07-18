<?php

namespace App\Admin\Controllers;

use App\Models\Document;
use Illuminate\Support\Facades\Storage;
use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;

/**
 * Gestion unifiee de tous les documents du club, toutes disciplines confondues.
 *
 * Remplace les anciens ecrans de documents par discipline : un seul menu, la
 * categorie (discipline) affichee en label et filtrable.
 */
class AdminDocumentController extends AdminController
{
    protected $title = 'Documents';

    private const DISCIPLINES = [
        1 => 'Blackball',
        2 => 'Carambole',
        3 => 'Snooker',
        4 => 'Américain',
    ];

    private const COLORS = [
        1 => 'bg-danger',
        2 => 'bg-warning',
        3 => 'bg-success',
        4 => 'bg-info',
    ];

    protected function grid()
    {
        $grid = new Grid(new Document);

        $grid->model()->orderBy('discipline')->orderBy('title');

        $grid->column('title', __('Titre'));
        $grid->column('discipline', __('Catégorie'))->display(function ($discipline) {
            $color = self::COLORS[$discipline] ?? 'bg-secondary';
            $name = self::DISCIPLINES[$discipline] ?? 'Autre';

            return "<span class='badge {$color}'>{$name}</span>";
        });
        $grid->column('file', __('Fichier'))->display(function ($file) {
            if (empty($file)) {
                return '-';
            }

            return "<a href='".asset('storage/'.$file)."' target='_blank'>Ouvrir le PDF</a>";
        });

        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
            $filter->like('title', __('Titre'));
            $filter->equal('discipline', __('Catégorie'))->select(self::DISCIPLINES);
        });

        return $grid;
    }

    protected function detail($id)
    {
        $show = new Show(Document::findOrFail($id));

        $show->field('title', __('Titre'));
        $show->field('discipline', __('Catégorie'))->as(function ($discipline) {
            return self::DISCIPLINES[$discipline] ?? 'Autre';
        });
        $show->field('file', __('Fichier'))->unescape()->as(function ($file) {
            if (empty($file)) {
                return '';
            }
            $url = Storage::disk('public')->url($file);

            return "<iframe src='{$url}' width='100%' height='800px' style='border:none;'></iframe>";
        });

        return $show;
    }

    protected function form()
    {
        $form = new Form(new Document);

        $form->text('title', __('Titre'))->required()
            ->help('Nom du document tel qu\'il apparaitra sur le site.');
        $form->select('discipline', __('Catégorie'))->options(self::DISCIPLINES)->required()
            ->help('Discipline a laquelle se rattache le document.');
        $form->file('file', __('Fichier PDF'))->disk('public')->move('pdf/documents')->uniqueName()
            // Restreint aux PDF : evite le depot de fichiers actifs (.php, .svg,
            // .html...) sur le disque public (defense en profondeur).
            ->rules('mimes:pdf|max:20480')
            ->help('Fichier PDF a mettre a disposition (classement, reglement, convocation...). 20 Mo maximum.');

        return $form;
    }
}
