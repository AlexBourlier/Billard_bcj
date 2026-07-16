<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\Partenaire;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;
use OpenAdmin\Admin\Controllers\AdminController;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;

class AdminPartenairesController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Partenaire';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Partenaire());

        $grid->model()->orderBy('ordre')->orderBy('id');

        $grid->column('ordre', __('Ordre'))->sortable();
        $grid->column('titre', __('Titre'));
        $grid->column('img', __('Image'))->display(function ($thumbnail) {
            // Vérifie si le thumbnail existe et est non vide
            if (empty($thumbnail)) {
                return ''; // Si aucun thumbnail n'est présent, rien n'est affiché
            }

            // Sinon, affiche l'image
            return '<img src="' . asset('storage/' . $thumbnail) . '" alt="Thumbnail" class="object-cover" style="width:48px; height:auto;">';
        });
        $grid->column('url', __('URL'));
        $grid->column('actif', __('Actif'))->display(function ($v) {
            return $v
                ? '<span class="badge badge-success">actif</span>'
                : '<span class="badge badge-default">inactif</span>';
        });
        $grid->column('date_fin', __('Fin'));

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
        $show = new Show(Partenaire::findOrFail($id));

        $show->field('titre', __('Titre'));
        $show->field('img', __('Image'))->unescape()->as(function ($thumbnail) {
            if (empty($thumbnail)) {
                return ''; // Ne rien afficher si le thumbnail est vide
            }
        
            return '<img src="' . asset('storage/' . $thumbnail) . '" alt="Thumbnail" class="object-cover" style="width:192px; height:auto;">';
        });
        $show->field('alt', __('Texte alternatif'));
        $show->field('url', __('URL'));
        $show->field('ordre', __('Ordre d\'affichage'));
        $show->field('actif', __('Actif'))->as(fn ($v) => $v ? 'Oui' : 'Non');
        $show->field('date_debut', __('Debut du partenariat'));
        $show->field('date_fin', __('Fin du partenariat'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Partenaire());

        $form->text('titre', __('Titre'))->required();
        $form->file('img', __('Image'))->removable()
            ->help('Logo du partenaire. Format conseille : PNG ou JPG, fond transparent ou blanc.');
        $form->ignore(['img']);
        $form->text('alt', __('Texte alternatif'))
            ->help('Decrit le logo pour l\'accessibilite et les lecteurs d\'ecran (ex : « Logo Intersport »). Si vide, le nom est utilise.');
        $form->url('url', __('Site web (URL)'))
            ->help('Facultatif. Si renseignee, le logo devient cliquable vers le site du partenaire.');
        $form->number('ordre', __('Ordre d\'affichage'))->default(0)
            ->help('Plus le nombre est petit, plus le partenaire apparait tot dans le carrousel.');
        $form->switch('actif', __('Actif'))->default(true)
            ->help('Desactiver masque le partenaire du site public, sans le supprimer.');
        $form->date('date_debut', __('Debut du partenariat'))
            ->help('Facultatif. Avant cette date, le partenaire n\'est pas affiche.');
        $form->date('date_fin', __('Fin du partenariat'))
            ->help('Facultatif. Apres cette date, le partenaire n\'est plus affiche (mais reste enregistre).');

        $form->saving(function ($form) {
            /** @var \Illuminate\Http\UploadedFile|null $file */
            $file = request()->file('img');
            if (!$file) return;

            $manager = new ImageManager(new GdDriver());
            $image   = $manager->read($file);

            // Limite la taille de l’original si très large
            if ($image->width() > 1600) {
                $image = $image->scale(width: 1600);
            }

            // Miniature : on relit le fichier (au lieu d’un clone)
            $thumb = $manager->read($file)->scale(width: 175);

            $dir      = 'partenaires';
            $basename = (string) Str::uuid();

            $originalData = (string) $image->toWebp(quality: 82);
            $thumbData    = (string) $thumb->toWebp(quality: 82);

            $originalPath = "{$dir}/{$basename}.webp";
            $thumbPath    = "{$dir}/{$basename}@175.webp";

            Storage::disk('public')->put($originalPath, $originalData);
            Storage::disk('public')->put($thumbPath, $thumbData);

            // Nettoyage ancien fichier si update
            if ($form->model()->exists && $form->model()->getOriginal('img')) {
                $old = $form->model()->getOriginal('img'); // ex: partenaires/xxx.webp
                $oldThumb = preg_replace('/(\.\w+)$/', '@175.webp', $old);
                Storage::disk('public')->delete([$old, $oldThumb]);
            }

            $form->model()->img = $originalPath;
            // $form->model()->thumb = $thumbPath; // si tu as une colonne dédiée
        });

        return $form;
    }
}
