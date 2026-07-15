<?php

namespace App\Admin\Controllers;

use Storage;
use App\Models\Menu;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use OpenAdmin\Admin\Admin;
use \App\Models\SiteSetting;
use OpenAdmin\Admin\Layout\Content;
use OpenAdmin\Admin\Controllers\AdminController;

class SiteSettingController extends AdminController
{
    protected $title = 'Paramètres du site';

    public function index(Content $content)
    {
        return $content
            ->title($this->title)
            ->description('Paramètre du site')
            ->body($this->grid());
    }

    protected function grid()
    {
        $grid = new Grid(new SiteSetting());
        $grid->column('id', __('Id'));
        $grid->column('logo', __('Logo'));
        $grid->column('banniere', __('Bannière'));
        $grid->column('adresse', __('Adresse'));
        $grid->column('telephone', __('Telephone'));
        $grid->column('email', __('Email'));
        $grid->column('youtube_page', __('Youtube page'));
        $grid->column('facebook_page', __('Facebook page'));
        $grid->column('facebook_token', __('Facebook token'));
        $grid->column('facebook_page_id', __('Facebook page id'));
        $grid->column('google_map_api', __('Google map api'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

        return $grid;
    }

    protected function detail($id)
    {
        $show = new Show(SiteSetting::findOrFail($id));
        $show->field('id', __('Id'));
        $show->field('logo', __('Logo'));
        $show->field('banniere', __('Bannière'));
        $show->field('adresse', __('Adresse'));
        $show->field('telephone', __('Telephone'));
        $show->field('email', __('Email'));
        $show->field('youtube_page', __('Youtube page'));
        $show->field('facebook_page', __('Facebook page'));
        $show->field('facebook_token', __('Facebook token'));
        $show->field('facebook_page_id', __('Facebook page id'));
        $show->field('google_map_api', __('Google map api'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    protected function form()
    {
        $form = new Form(new SiteSetting());

        $form->image('logo', 'Logo du site')
            ->disk('public')
            ->move('img')
            ->uniqueName()
            ->help('Ratio recommandé : 706x349 px');

        $form->image('banniere', 'Bannière du site')
            ->disk('public')
            ->move('img')
            ->uniqueName()
            ->rules('image|max:8192')
            ->help('Ratio recommandé : 3222x964 px');

        $form->text('adresse', 'Adresse');
        $form->text('telephone', 'Téléphone')
            ->rules('nullable|regex:/^0[1-9]( ?\d{2}){4}$/')
            ->help('Format attendu : 06 12 34 56 78 (laisser vide si non renseigné)');

        Admin::script(<<<'JS'
            document.addEventListener('DOMContentLoaded', function () {
                const telInput = document.querySelector('input[name="telephone"]');
                if (telInput) {
                    telInput.setAttribute('placeholder', '06 12 34 56 78');
                    telInput.addEventListener('input', function (e) {
                        let numbers = e.target.value.replace(/\D/g, '');
                        let result = '';
                        for (let i = 0; i < numbers.length && i < 10; i += 2) {
                            result += numbers.substr(i, 2) + ' ';
                        }
                        e.target.value = result.trim();
                    });
                }
            });
        JS);

        $form->email('email', 'Email');
        $form->url('youtube_page', 'Page Youtube');
        $form->url('facebook_page', 'Page Facebook');
        // $form->text('facebook_token', 'Facebook Access Token')->attribute(['class' => 'api-field']);
        // $form->text('facebook_page_id', 'Facebook Page ID')->attribute(['class' => 'api-field']);
        // $form->text('google_map_api', 'Clé API Google Maps')->attribute(['class' => 'api-field']);
        $form->text('facebook_token', 'Facebook Access Token');
        $form->text('facebook_page_id', 'Facebook Page ID');
        $form->text('google_map_api', 'Clé API Google Maps');

        $form->tools(function (Form\Tools $tools) {
            $tools->disableDelete();
            $tools->disableView();
        });

        $form->footer(function ($footer) {
            $footer->disableReset();
        });

        return $form;
    }
}
