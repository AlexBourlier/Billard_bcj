<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Retire du menu les anciens ecrans de classement CueScore par discipline
 * (americain-classements, snooker-classements), deconnectes du pipeline et
 * remplaces par la section "Classements CueScore" (CRUD sur cuescore_rankings).
 *
 * On ne touche pas a classement-caramboles (classement PDF, feature legitime),
 * ni aux controleurs/tables : seules les entrees de menu sont retirees.
 */
return new class extends Migration
{
    private const LEGACY_URIS = [
        'americain-classements',
        'snooker-classements',
    ];

    public function up(): void
    {
        DB::table('admin_menu')->whereIn('uri', self::LEGACY_URIS)->delete();
    }

    public function down(): void
    {
        // Retrait volontaire : les entrees ne sont pas recreees.
    }
};
