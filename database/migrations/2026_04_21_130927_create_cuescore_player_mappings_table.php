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
        Schema::create('cuescore_player_mappings', function (Blueprint $table) {
            $table->id();

            $table->string('cuescore_participant_id')->unique();
            $table->string('cuescore_name');
            $table->string('cuescore_url')->nullable();
            
            // Nullable : un participant CueScore sans correspondance fiable reste
            // stocke avec licencie_id = null (voir CueScorePlayerMatcher).
            $table->foreignId('licencie_id')
                ->nullable()
                ->constrained('licencies')
                ->nullOnDelete();

            $table->string('matching_method', 30)->index();
            $table->unsignedTinyInteger('confidence_score')->nullable();
            $table->boolean('is_confirmed')->default(false)->index();
            $table->text('notes')->nullable();
            
            $table->index(['licencie_id', 'is_confirmed']);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cuescore_player_mappings');
    }
};
