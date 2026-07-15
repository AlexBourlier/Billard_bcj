<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Ajoute l'entree de menu OpenAdmin "Correspondances CueScore" sous la
 * section "Administration" (parent_id = 40).
 */
return new class extends Migration
{
    private const URI = 'cuescore-mappings';
    private const PARENT_ID = 40; // section "Administration"

    public function up(): void
    {
        if (DB::table('admin_menu')->where('uri', self::URI)->exists()) {
            return;
        }

        $order = (int) DB::table('admin_menu')
            ->where('parent_id', self::PARENT_ID)
            ->max('order');

        DB::table('admin_menu')->insert([
            'parent_id'  => self::PARENT_ID,
            'order'      => $order + 1,
            'title'      => 'Correspondances CueScore',
            'icon'       => 'icon-user-check',
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
