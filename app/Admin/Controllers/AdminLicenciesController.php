<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\Licencies;
use OpenAdmin\Admin\Layout\Content;
use OpenAdmin\Admin\Controllers\AdminController;

class AdminLicenciesController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Licencies';

    public function licencies(Content $content)
    {
        $nbLicencies = Licencies::count();

        return $content
            ->title('Tableau de bord')
            ->description('Bienvenue dans l\'administration')
            ->row(view('admin.club.licencies', compact('nbLicencies')));
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Licencies());

        $grid->column('id', __('Id'));
        $grid->column('licence', __('Licence'));
        $grid->column('nom', __('Nom'));
        $grid->column('prenom', __('Prenom'));
        $grid->column('url', __('Url'));

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Licencies::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('licence', __('Licence'));
        $show->field('nom', __('Nom'));
        $show->field('prenom', __('Prenom'));
        $show->field('url', __('Url'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Licencies());

        $form->text('licence', __('Licence'));
        $form->text('nom', __('Nom'));
        $form->text('prenom', __('Prenom'));
        $form->url('url', __('Url'));

        return $form;
    }
}
