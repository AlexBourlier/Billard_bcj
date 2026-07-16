<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Bloc d'information important, administrable, affiche sur la page d'accueil :
 * fermeture exceptionnelle, changement d'horaire, inscriptions, tournoi, info
 * urgente. Un bloc peut etre desactive ou sortir de sa fenetre de dates sans
 * etre supprime.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('info_blocks', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->text('resume')->nullable();
            // Niveau d'importance : info | important | urgent (pilote la couleur).
            $table->string('niveau')->default('info');
            $table->string('lien')->nullable();
            $table->date('date_debut')->nullable();
            $table->date('date_fin')->nullable();
            $table->boolean('actif')->default(true);
            $table->unsignedInteger('ordre')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('info_blocks');
    }
};
