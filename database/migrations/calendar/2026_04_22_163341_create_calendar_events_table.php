<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('calendar_events', function (Blueprint $table) {
            $table->id();

            $table->foreignId('calendar_id')
                ->constrained('calendars')
                ->cascadeOnDelete();

            $table->string('external_id')->nullable();

            $table->dateTime('date_debut');
            $table->dateTime('date_fin');
            $table->dateTime('date_limite')->nullable();

            $table->string('titre');
            $table->string('lieu');
            $table->string('club')->nullable();

            $table->text('url')->nullable();
            $table->string('status', 100)->nullable();

            $table->json('source_payload')->nullable();

            $table->timestamps();

            $table->index('calendar_id', 'calendar_events_calendar_id_index');
            $table->index('date_debut', 'calendar_events_date_debut_index');
            $table->index(['calendar_id', 'date_debut'], 'calendar_events_calendar_id_date_debut_index');

            $table->unique(['calendar_id', 'external_id'], 'calendar_events_calendar_id_external_id_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('calendar_events');
    }
};