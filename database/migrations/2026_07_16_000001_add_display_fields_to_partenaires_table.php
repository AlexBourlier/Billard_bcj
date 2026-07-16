<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Champs d'affichage des partenaires (carrousel page d'accueil) :
 * ordre, statut actif, texte alternatif et fenetre de partenariat.
 *
 * Un partenaire dont le partenariat est termine n'est pas supprime : il est
 * desactive (actif = false) ou sort de la fenetre date_debut / date_fin.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('partenaires', function (Blueprint $table) {
            if (! Schema::hasColumn('partenaires', 'ordre')) {
                $table->unsignedInteger('ordre')->default(0)->after('url');
            }
            if (! Schema::hasColumn('partenaires', 'actif')) {
                $table->boolean('actif')->default(true)->after('ordre');
            }
            if (! Schema::hasColumn('partenaires', 'alt')) {
                $table->string('alt')->nullable()->after('actif');
            }
            if (! Schema::hasColumn('partenaires', 'date_debut')) {
                $table->date('date_debut')->nullable()->after('alt');
            }
            if (! Schema::hasColumn('partenaires', 'date_fin')) {
                $table->date('date_fin')->nullable()->after('date_debut');
            }
        });

        // Ordre initial stable pour les partenaires existants (par id).
        foreach (DB::table('partenaires')->orderBy('id')->pluck('id') as $index => $id) {
            DB::table('partenaires')->where('id', $id)->update(['ordre' => $index + 1]);
        }
    }

    public function down(): void
    {
        Schema::table('partenaires', function (Blueprint $table) {
            $table->dropColumn(['ordre', 'actif', 'alt', 'date_debut', 'date_fin']);
        });
    }
};
