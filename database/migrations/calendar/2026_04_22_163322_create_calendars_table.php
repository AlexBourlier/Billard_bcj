<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('calendars', function (Blueprint $table) {
            $table->id();

            $table->string('discipline', 50);
            $table->string('scope', 50);
            $table->string('name')->nullable();
            $table->string('slug')->unique();
            $table->string('source_type', 50)->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->unique(['discipline', 'scope'], 'calendars_discipline_scope_unique');
            $table->index('is_active', 'calendars_is_active_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('calendars');
    }
};