<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Reorganisation du menu OpenAdmin pour le rendre plus lisible :
 *  - « Contenu du site » ne contient plus que du contenu (+ Documents rapatrie) ;
 *  - les licencies deviennent une SECTION dediee (au lieu d'etre noyes dans
 *    « Contenu du site ») ;
 *  - la configuration est regroupee dans une section « Parametres »
 *    (Parametres du site + Menus du site).
 *
 * Toutes les operations sont reperees par URI (stable d'une base a l'autre) et
 * idempotentes : la migration peut etre rejouee sans dupliquer ni casser.
 */
return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        $topSection = function (string $title) {
            return DB::table('admin_menu')
                ->where('parent_id', 0)
                ->where('title', $title)
                ->whereRaw("(uri IS NULL OR uri = '')")
                ->first();
        };

        $ensureSection = function (string $title, string $icon, int $order) use ($topSection, $now) {
            $existing = $topSection($title);
            if ($existing) {
                // Met a jour l'ordre / l'icone meme si la section existe deja
                // (idempotence : la migration peut etre rejouee proprement).
                DB::table('admin_menu')->where('id', $existing->id)->update([
                    'order' => $order,
                    'icon' => $icon,
                    'updated_at' => $now,
                ]);

                return (int) $existing->id;
            }

            return (int) DB::table('admin_menu')->insertGetId([
                'parent_id' => 0,
                'order' => $order,
                'title' => $title,
                'icon' => $icon,
                'uri' => '',
                'permission' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        };

        $move = function (string $uri, int $parentId, int $order, ?string $newTitle = null) use ($now) {
            $data = ['parent_id' => $parentId, 'order' => $order, 'updated_at' => $now];
            if ($newTitle !== null) {
                $data['title'] = $newTitle;
            }
            DB::table('admin_menu')->where('uri', $uri)->update($data);
        };

        // 1) Section « Licencies » + rattachement des entrees licencies.
        $licencies = $ensureSection('Licenciés', 'icon-users', 5);
        $move('licencies', $licencies, 1, 'Liste des licenciés');
        $move('license-import/batches', $licencies, 2, 'Import des licenciés');
        $move('cuescore-mappings', $licencies, 3, 'Correspondances CueScore');

        // 2) Documents rapatrie dans « Contenu du site ».
        if ($contenu = $topSection('Contenu du site')) {
            $move('documents', (int) $contenu->id, 50);
        }

        // 3) Section « Parametres » : Parametres du site + Menus du site.
        $parametres = $ensureSection('Paramètres', 'icon-cog', 50);
        $move('site-settings', $parametres, 1, 'Paramètres du site');
        $move('menus', $parametres, 2, 'Menus du site');
    }

    public function down(): void
    {
        $now = now();

        $topByTitle = fn (string $t) => DB::table('admin_menu')
            ->where('parent_id', 0)->where('title', $t)
            ->whereRaw("(uri IS NULL OR uri = '')")->first();

        // Remettre les entrees deplacees dans « Contenu du site » / au niveau 1.
        if ($contenu = $topByTitle('Contenu du site')) {
            foreach (['licencies', 'license-import/batches', 'cuescore-mappings', 'menus'] as $uri) {
                DB::table('admin_menu')->where('uri', $uri)
                    ->update(['parent_id' => (int) $contenu->id, 'updated_at' => $now]);
            }
        }
        DB::table('admin_menu')->whereIn('uri', ['documents', 'site-settings'])
            ->update(['parent_id' => 0, 'updated_at' => $now]);

        // Supprimer les sections creees si elles sont vides.
        foreach (['Licenciés', 'Paramètres'] as $title) {
            $section = $topByTitle($title);
            if ($section && DB::table('admin_menu')->where('parent_id', $section->id)->doesntExist()) {
                DB::table('admin_menu')->where('id', $section->id)->delete();
            }
        }
    }
};
