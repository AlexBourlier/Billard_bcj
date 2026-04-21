<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cuescore_ranking_entries', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('cuescore_ranking_id');
            $table->unsignedBigInteger('cuescore_ranking_fetch_id');

            $table->string('entry_type', 30)->index();
            $table->unsignedInteger('rank_position')->nullable();

            $table->string('participant_name')->nullable();
            $table->string('participant_external_id')->nullable()->index();
            $table->text('participant_url')->nullable();

            $table->string('team_name')->nullable();
            $table->string('team_external_id')->nullable()->index();
            $table->text('team_url')->nullable();

            $table->decimal('points', 10, 2)->nullable();
            $table->unsignedInteger('played')->nullable();
            $table->unsignedInteger('wins')->nullable();
            $table->unsignedInteger('losses')->nullable();
            $table->unsignedInteger('ties')->nullable();

            $table->json('additional_data')->nullable();

            $table->index(['cuescore_ranking_id', 'entry_type'], 'cs_entries_ranking_entry_type_idx');
            $table->index(['cuescore_ranking_fetch_id', 'entry_type'], 'cs_entries_fetch_entry_type_idx');

            $table->foreign('cuescore_ranking_id', 'cs_entries_ranking_fk')
                ->references('id')
                ->on('cuescore_rankings')
                ->cascadeOnDelete();

            $table->foreign('cuescore_ranking_fetch_id', 'cs_entries_fetch_fk')
                ->references('id')
                ->on('cuescore_ranking_fetches')
                ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cuescore_ranking_entries');
    }
};