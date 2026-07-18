<?php

namespace App\Admin\Controllers;

use App\Models\SiteSetting;
use App\Support\ImageOptimizer;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use OpenAdmin\Admin\Admin;
use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Layout\Content;
use OpenAdmin\Admin\Show;

class SiteSettingController extends AdminController
{
    protected $title = 'Paramètres du site';

    public function index(Content $content)
    {
        return $content
            ->title($this->title)
            ->description('Identité, coordonnées et réseaux du club')
            ->body($this->grid());
    }

    /** Aperçu image (miniature) a partir d'un chemin stocke dans /storage. */
    private static function imagePreview(?string $path, int $height): string
    {
        if (empty($path)) {
            return '<span class="text-muted">—</span>';
        }

        return '<img src="'.asset('storage/'.$path).'" alt="" style="height:'.$height.'px; width:auto; border-radius:6px;">';
    }

    protected function grid()
    {
        $grid = new Grid(new SiteSetting);

        // Une seule ligne de reglages : on n'affiche que l'essentiel, avec les
        // visuels en apercu. Pas de creation ni de suppression (reglages uniques).
        $grid->disableCreateButton();
        $grid->actions(function ($actions) {
            $actions->disableDelete();
        });

        // On capture le rendu dans une variable : les closures display()/as()
        // sont re-liees au scope du modele par OpenAdmin, ou « self:: » ne
        // pointerait plus vers ce controleur.
        $preview = \Closure::fromCallable([self::class, 'imagePreview']);

        $grid->column('logo', __('Logo'))->display(fn ($v) => $preview($v, 40));
        $grid->column('banniere', __('Bannière'))->display(fn ($v) => $preview($v, 30));
        $grid->column('adresse', __('Adresse'));
        $grid->column('telephone', __('Téléphone'));
        $grid->column('email', __('Email'));

        return $grid;
    }

    protected function detail($id)
    {
        $show = new Show(SiteSetting::findOrFail($id));

        // Voir grid() : « self:: » ne fonctionne pas dans une closure as(),
        // re-liee au scope du modele. On passe par une variable capturee.
        $preview = \Closure::fromCallable([self::class, 'imagePreview']);

        $show->field('logo', __('Logo'))->unescape()->as(fn ($v) => $preview($v, 90));
        $show->field('banniere', __('Bannière'))->unescape()->as(fn ($v) => $preview($v, 70));
        $show->field('adresse', __('Adresse'));
        $show->field('telephone', __('Téléphone'));
        $show->field('email', __('Email'));
        $show->field('youtube_page', __('Page YouTube'));
        $show->field('facebook_page', __('Page Facebook'));
        $show->field('facebook_page_id', __('Identifiant page Facebook'));
        $show->field('updated_at', __('Dernière modification'));

        return $show;
    }

    protected function form()
    {
        $form = new Form(new SiteSetting);

        // --- Identite visuelle ---------------------------------------------
        // Le logo et la banniere sont recompresses en WebP a l'enregistrement
        // (voir saving() plus bas) : les fichiers uploades ne sont jamais
        // stockes bruts, pour ne pas alourdir le chargement du site.
        $form->fieldset(__('Identité visuelle'), function (Form $form) {
            $form->file('logo', 'Logo du club')
                ->removable()
                ->rules('nullable|image|max:8192')
                ->help('Logo affiché sur le site. Ratio recommandé : 706 × 349 px. Converti automatiquement en WebP.');

            $form->file('banniere', 'Bannière d\'accueil')
                ->removable()
                ->rules('nullable|image|max:8192')
                ->help('Grande image en haut de la page d\'accueil. Ratio recommandé : 3222 × 964 px. Convertie automatiquement en WebP.');
        });

        // --- Coordonnees ----------------------------------------------------
        $form->fieldset(__('Coordonnées du club'), function (Form $form) {
            $form->text('adresse', 'Adresse')
                ->help('Adresse complète (utilisée sur la carte « Nous trouver »).');
            $form->text('telephone', 'Téléphone')
                ->rules('nullable|regex:/^0[1-9]( ?\d{2}){4}$/')
                ->help('Format : 06 12 34 56 78 (laisser vide si non renseigné).');
            $form->email('email', 'Email de contact');
        });

        // --- Reseaux sociaux ------------------------------------------------
        $form->fieldset(__('Réseaux sociaux'), function (Form $form) {
            $form->url('youtube_page', 'Page YouTube')
                ->help('Adresse complète de la chaîne (https://…).');
            $form->url('facebook_page', 'Page Facebook')
                ->help('Adresse complète de la page (https://…).');
            $form->text('facebook_page_id', 'Identifiant de la page Facebook')
                ->help('Facultatif — utilisé pour l\'intégration Facebook.');
        });

        // OpenAdmin ne doit pas enregistrer le fichier brut : on gere logo et
        // banniere nous-memes dans saving() (conversion WebP + compression).
        $form->ignore(['logo', 'banniere']);

        $form->saving(function (Form $form) {
            // Reglages alignes sur la commande images:optimize (coherence).
            $process = function (string $column, int $maxWidth, int $quality) use ($form) {
                $file = request()->file($column);
                if (! $file) {
                    return;
                }

                $path = 'img/'.Str::uuid().'.webp';
                Storage::disk('public')->put($path, ImageOptimizer::toWebp($file, $maxWidth, $quality));

                // Supprime l'ancien fichier lors d'une mise a jour.
                $old = $form->model()->getOriginal($column);
                if ($old) {
                    Storage::disk('public')->delete($old);
                }

                $form->model()->{$column} = $path;
            };

            $process('logo', 800, 85);
            $process('banniere', 2000, 82);
        });

        // Aide a la saisie du telephone (mise en forme automatique).
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
