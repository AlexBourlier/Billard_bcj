<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Menu OpenAdmin pour le CRUD des classements CueScore :
 * - cree une section "Classements CueScore" avec une entree par combo
 *   discipline + portee (chaque lien pre-filtre l'ecran param-driven) ;
 * - retire les anciennes entrees "Liens Cuescore" (tables legacy cuescore_national
 *   etc.), deconnectees du pipeline, pour eviter la confusion.
 */
return new class extends Migration
{
    /** [discipline, scope (valeur DB), libelle]. */
    private const COMBOS = [
        ['blackball', 'national',       'Blackball · National'],
        ['blackball', 'regional',       'Blackball · Régional'],
        ['blackball', 'départemental',  'Blackball · Départemental'],
        ['americain', 'national',       'Américain · National'],
        ['americain', 'regional',       'Américain · Régional'],
        ['snooker',   'national',       'Snooker · National'],
        ['snooker',   'départemental',  'Snooker · Départemental'],
    ];

    /** Entrees de menu legacy "Liens Cuescore" a retirer (uris). */
    private const LEGACY_URIS = [
        'cuescore-nationals',
        'cuescore-regionals',
        'departemental-links',
        'cuescore-equipes-nationales',
        'cuescore-equipes-regionales',
    ];

    public function up(): void
    {
        // 1) Section parente (idempotent).
        $parent = DB::table('admin_menu')->where('title', 'Classements CueScore')->first();

        if (! $parent) {
            $order = (int) DB::table('admin_menu')->where('parent_id', 0)->max('order');
            $parentId = DB::table('admin_menu')->insertGetId([
                'parent_id'  => 0,
                'order'      => $order + 1,
                'title'      => 'Classements CueScore',
                'icon'       => 'icon-trophy',
                'uri'        => '',
                'permission' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $parentId = $parent->id;
        }

        // 2) Une entree par combo discipline + portee.
        foreach (self::COMBOS as $i => [$discipline, $scope, $label]) {
            $uri = 'cuescore-classements?discipline=' . $discipline . '&scope=' . $scope;

            if (DB::table('admin_menu')->where('uri', $uri)->exists()) {
                continue;
            }

            DB::table('admin_menu')->insert([
                'parent_id'  => $parentId,
                'order'      => $i + 1,
                'title'      => $label,
                'icon'       => 'icon-list',
                'uri'        => $uri,
                'permission' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 3) Retrait des entrees legacy "Liens Cuescore".
        $legacyParents = DB::table('admin_menu')
            ->whereIn('uri', self::LEGACY_URIS)
            ->pluck('parent_id')
            ->unique();

        DB::table('admin_menu')->whereIn('uri', self::LEGACY_URIS)->delete();

        // Retire les sections parentes "Classements" devenues vides.
        foreach ($legacyParents as $pid) {
            if ($pid && ! DB::table('admin_menu')->where('parent_id', $pid)->exists()) {
                DB::table('admin_menu')->where('id', $pid)->where('uri', '')->delete();
            }
        }
    }

    public function down(): void
    {
        foreach (self::COMBOS as [$discipline, $scope]) {
            DB::table('admin_menu')
                ->where('uri', 'cuescore-classements?discipline=' . $discipline . '&scope=' . $scope)
                ->delete();
        }

        DB::table('admin_menu')->where('title', 'Classements CueScore')->where('uri', '')->delete();
        // Les entrees legacy ne sont pas recreees (retrait volontaire).
    }
};
