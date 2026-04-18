<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('national_sport_easy', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('national_id')->nullable();
            // $table->foreign('national_id')->references('id')->on('calendrier_national')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('national_sport_easy');
    }
};