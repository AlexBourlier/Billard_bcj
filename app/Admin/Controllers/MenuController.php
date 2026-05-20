<?php

namespace App\Admin\Controllers;

use \App\Models\Menu;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use Illuminate\Support\Facades\Cache;
use OpenAdmin\Admin\Controllers\AdminController;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;


class MenuController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Menu';

    

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Menu());
        $colors = [
            0 => 'bg-danger',    // inactif → rouge
            1 => 'bg-success',   // actif → vert
        ];

        $grid->column('nom', __('Nom'));
        $grid->column('image', __('Image'))->display(function ($thumbnail) {
            // Vérifie si le thumbnail existe et est non vide
            if (empty($thumbnail)) {
                return ''; // Si aucun thumbnail n'est présent, rien n'est affiché
            }

            // Sinon, affiche l'image
            return '<img src="' . asset('storage/' . $thumbnail) . '" alt="Thumbnail" class="object-cover" style="width:48px; height:auto;">';
        });
        $grid->column('actif', __('Statut'))->display(function ($value) use ($colors) {
            $color = $colors[$value] ?? 'bg-secondary';
            $name = $value == 1 ? 'Actif' : 'Inactif';
        
            return "<span class='badge {$color}' style='padding:6px 12px; font-size:13px;'>{$name}</span>";
        });

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
        $show = new Show(Menu::findOrFail($id));
        $colors = [
            0 => 'bg-danger',    // inactif → rouge
            1 => 'bg-success',   // actif → vert
        ];

        $show->field('nom', __('Nom'));
        $show->field('image', __('Image'))->unescape()->as(function ($thumbnail) {
            if (empty($thumbnail)) {
                return ''; // Ne rien afficher si le thumbnail est vide
            }
        
            return '<img src="' . asset('storage/' . $thumbnail) . '" alt="Thumbnail" class="object-cover" style="width:192px; height:auto;">';
        });
        $show->field('actif', __('Actif'))->unescape()->as(function ($value) use ($colors) {
            $color = $colors[$value] ?? 'bg-secondary';
            $name = $value == 1 ? 'Actif' : 'Inactif';

            return "<span class='badge {$color}' style='padding:6px 12px; font-size:13px;'>{$name}</span>";
        });

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Menu());

        $form->text('nom', __('Nom'));

        // Upload temporaire
        $form->file('image_upload', __('Image'))->removable();

        // Ignore le champ temporaire
        $form->ignore(['image_upload']);

        $form->switch('actif', __('Actif'))->default(1);

        $form->saving(function ($form) {

            if (request()->hasFile('image_upload')) {

                $file = request()->file('image_upload');

                // Nom unique
                $filename = Str::random(12) . '.webp';

                $manager = new ImageManager(new GdDriver());

                $image = $manager->read($file);

                // Redimensionnement optionnel
                if ($image->width() > 1200) {
                    $image = $image->scale(width: 1200);
                }

                // Conversion WebP qualité 60
                $webpData = $image->toWebp(quality: 60)->toString();

                // Sauvegarde
                Storage::disk('public')->put(
                    'menu/' . $filename,
                    $webpData
                );

                // Enregistrement BDD
                $form->model()->image = 'menu/' . $filename;
            }
        });

        return $form;
    }

    public function toggle($id)
    {
        $menu = Menu::findOrFail($id);
        $menu->actif = !$menu->actif;
        $menu->save();

        // Vider le cache après la mise à jour
        // Cache::forget('menus_actifs');

        return response()->json(['success' => true, 'actif' => $menu->actif]);
    }
}
