<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('regional_links', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('calendrier_id')->nullable();
            $table->string('top_ligue')->nullable();
            $table->string('mixte')->nullable();
            $table->string('feminin')->nullable();
            $table->string('U18')->nullable();
            $table->string('U15')->nullable();
            $table->string('U23')->nullable();
            $table->string('handi')->nullable();
            $table->string('veteran')->nullable();
            $table->string('handi_fauteuil')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('regional_links');
    }
};