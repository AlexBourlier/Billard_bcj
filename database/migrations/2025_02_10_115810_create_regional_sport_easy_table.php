<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('regional_sport_easy', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('regional_id')->nullable();
            // $table->foreign('regional_id')->references('id')->on('calendrier_regional')->onDelete('cascade');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('regional_sport_easy');
    }
};