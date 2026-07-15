<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

/**
 * Supprime les tables des anciens ecrans CueScore par discipline, remplaces par
 * le CRUD des classements CueScore (cuescore_rankings) et deconnectes du pipeline.
 *
 * Donnees minimales (8 lignes au total) et non lues par du code actif.
 */
return new class extends Migration
{
    private const TABLES = [
        'cuescore_national',
        'cuescore_regional',
        'cuescore_equipes_nationales',
        'cuescore_equipes_regionales',
        'americain_classements',
        'snooker_classements',
    ];

    public function up(): void
    {
        foreach (self::TABLES as $table) {
            Schema::dropIfExists($table);
        }
    }

    public function down(): void
    {
        // Tables legacy volontairement non recreees.
    }
};
