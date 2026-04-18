<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('calendrier_national', function (Blueprint $table) {
            $table->id();
            $table->date('date_debut')->nullable();
            $table->date('date_fin')->nullable();
            $table->date('date_limite')->nullable();
            $table->string('titre')->nullable();
            $table->string('lieu')->nullable();
            $table->string('club')->nullable();
            $table->string('url')->nullable();
            $table->boolean('is_closed')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('calendrier_national');
    }
};