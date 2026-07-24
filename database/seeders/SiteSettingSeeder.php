<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Reglages minimaux du site (coordonnees) pour que la page d'accueil ne soit
 * pas vide a la premiere installation. A completer ensuite via l'administration.
 */
class SiteSettingSeeder extends Seeder
{
    public function run(): void
    {
        if (DB::table('site_settings')->exists()) {
            return;
        }

        DB::table('site_settings')->insert([
            'telephone' => '02 47 00 00 00',
            'email' => 'contact@bcj37.fr',
            'adresse' => 'Joue-les-Tours (37300)',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
