<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Regularise les colonnes de `site_settings` : `banniere`, `adresse` et
 * `youtube_page` sont utilisees par le formulaire d'administration et l'API,
 * mais n'existaient dans aucune migration versionnee (ajoutees manuellement en
 * base). Une installation fraiche en etait donc depourvue.
 *
 * Migration idempotente : chaque colonne n'est ajoutee que si elle est absente,
 * afin de ne pas echouer sur les bases (locale / production) ou elle existe deja.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            if (! Schema::hasColumn('site_settings', 'banniere')) {
                $table->string('banniere')->nullable()->after('logo');
            }
            if (! Schema::hasColumn('site_settings', 'adresse')) {
                $table->string('adresse')->nullable()->after('banniere');
            }
            if (! Schema::hasColumn('site_settings', 'youtube_page')) {
                $table->string('youtube_page')->nullable()->after('email');
            }
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            foreach (['banniere', 'adresse', 'youtube_page'] as $column) {
                if (Schema::hasColumn('site_settings', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
