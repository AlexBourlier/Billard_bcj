<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Table de file d'attente `database` : permet d'executer les imports declenches
 * depuis l'admin en arriere-plan (via un worker queue:work) au lieu de bloquer
 * la requete HTTP. La table `failed_jobs` existe deja.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('jobs')) {
            return;
        }

        Schema::create('jobs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('queue')->index();
            $table->longText('payload');
            $table->unsignedTinyInteger('attempts');
            $table->unsignedInteger('reserved_at')->nullable();
            $table->unsignedInteger('available_at');
            $table->unsignedInteger('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
