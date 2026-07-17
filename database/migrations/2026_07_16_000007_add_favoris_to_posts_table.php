<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Regularise la colonne `posts.favoris` : elle etait utilisee par le modele et
 * l'API (article « a la une ») mais n'existait dans aucune migration versionnee
 * (ajoutee manuellement en production). Cette absence cassait les installations
 * fraiches et `Post::factory()`.
 *
 * Migration idempotente : la colonne n'est ajoutee que si elle n'existe pas
 * deja, afin de ne pas echouer sur la base de production ou elle est presente.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('posts', 'favoris')) {
            Schema::table('posts', function (Blueprint $table) {
                $table->boolean('favoris')->default(false)->after('year');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('posts', 'favoris')) {
            Schema::table('posts', function (Blueprint $table) {
                $table->dropColumn('favoris');
            });
        }
    }
};
