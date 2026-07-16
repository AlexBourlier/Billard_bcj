<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Entree de menu OpenAdmin « Informations accueil », rangee dans la meme section
 * que les autres contenus du site (parent de l'entree « posts »).
 */
return new class extends Migration
{
    private const URI = 'info-blocks';

    public function up(): void
    {
        if (DB::table('admin_menu')->where('uri', self::URI)->exists()) {
            return;
        }

        // Rattache a la meme section que les actualites, quel que soit son
        // libelle actuel (Administration / Contenu du site selon les lots).
        $parentId = (int) DB::table('admin_menu')->where('uri', 'posts')->value('parent_id');

        $order = (int) DB::table('admin_menu')->where('parent_id', $parentId)->max('order');

        DB::table('admin_menu')->insert([
            'parent_id'  => $parentId,
            'order'      => $order + 1,
            'title'      => 'Informations accueil',
            'icon'       => 'icon-bullhorn',
            'uri'        => self::URI,
            'permission' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        DB::table('admin_menu')->where('uri', self::URI)->delete();
    }
};
