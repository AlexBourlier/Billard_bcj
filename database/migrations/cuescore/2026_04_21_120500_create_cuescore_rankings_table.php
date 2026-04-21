<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cuescore_rankings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('cuescore_id')->unique();
            $table->text('url');
            $table->string('source_type', 30);
            $table->string('discipline', 50)->index();
            $table->string('scope', 30)->index();
            $table->string('ranking_type', 30)->index();
            $table->string('team_category', 30)->nullable();
            $table->string('season', 20);
            $table->boolean('is_active')->default(false)->index();
            $table->integer('sort_order')->default(0);

            $table->index(['discipline', 'scope', 'ranking_type']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cuescore_rankings');
    }
};
