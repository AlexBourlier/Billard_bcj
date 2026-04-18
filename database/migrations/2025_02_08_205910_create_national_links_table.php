<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('national_links', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('calendrier_id')->nullable();
            $table->string('master')->nullable();
            $table->string('mixte_tableau_a')->nullable();
            $table->string('mixte_tableau_b')->nullable();
            $table->string('feminin')->nullable();
            $table->string('espoir')->nullable();
            $table->string('junior')->nullable();
            $table->string('handi')->nullable();
            $table->string('veteran')->nullable();
            $table->string('individuel')->nullable();
            $table->string('equipe')->nullable();
            $table->string('doublette')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('national_links');
    }
};