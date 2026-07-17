<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Entree de menu OpenAdmin « Documentation API » vers la doc Scribe (/docs),
 * rangee dans « Outils developpeur ».
 *
 * La doc est servie a la racine du domaine de l'API (hors du prefixe /admin) :
 * on stocke donc une URL ABSOLUE, calculee selon l'environnement courant au
 * moment de la migration (via url()), pour que admin_url() la renvoie telle
 * quelle (il ne prefixe pas /admin les URLs valides).
 */
return new class extends Migration
{
    private const TITLE = 'Documentation API';

    public function up(): void
    {
        if (DB::table('admin_menu')->where('title', self::TITLE)->exists()) {
            return;
        }

        // Section « Outils developpeur » si presente, sinon a la racine du menu.
        $parentId = (int) DB::table('admin_menu')
            ->where('title', 'Outils développeur')
            ->value('id');

        $order = (int) DB::table('admin_menu')->where('parent_id', $parentId)->max('order');

        DB::table('admin_menu')->insert([
            'parent_id' => $parentId,
            'order' => $order + 1,
            'title' => self::TITLE,
            'icon' => 'icon-book',
            'uri' => url(config('scribe.laravel.docs_url', '/docs')),
            'permission' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        DB::table('admin_menu')->where('title', self::TITLE)->delete();
    }
};
