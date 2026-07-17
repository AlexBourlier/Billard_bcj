<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Ajoute le workflow de publication des articles :
 * - `status`       : 'draft' (brouillon) ou 'published' (publie) ;
 * - `published_at` : date a partir de laquelle l'article est visible
 *   publiquement (une date future = publication programmee) ;
 * - `updated_by`   : identifiant de l'administrateur ayant modifie en dernier.
 *
 * Regle de visibilite publique : `status = 'published'` ET `published_at <= now`.
 *
 * Compatibilite : les articles existants deviennent « publies » (defaut) et
 * leur `published_at` est aligne sur `created_at` afin de rester visibles.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            if (! Schema::hasColumn('posts', 'status')) {
                $table->string('status', 20)->default('published')->after('favoris');
            }
            if (! Schema::hasColumn('posts', 'published_at')) {
                $table->timestamp('published_at')->nullable()->after('status');
            }
            if (! Schema::hasColumn('posts', 'updated_by')) {
                $table->unsignedBigInteger('updated_by')->nullable()->after('published_at');
            }
        });

        // Les articles deja en base sont consideres publies : on aligne
        // published_at sur created_at pour qu'ils restent visibles.
        DB::table('posts')
            ->whereNull('published_at')
            ->update(['published_at' => DB::raw('created_at')]);
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            foreach (['status', 'published_at', 'updated_by'] as $column) {
                if (Schema::hasColumn('posts', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
