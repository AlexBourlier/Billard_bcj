<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('calendar_event_links', function (Blueprint $table) {
            $table->id();

            $table->foreignId('calendar_event_id')
                ->constrained('calendar_events')
                ->cascadeOnDelete();

            $table->string('category', 100);
            $table->string('label')->nullable();
            $table->text('url');
            $table->integer('sort_order')->nullable();

            $table->timestamps();

            $table->index('calendar_event_id', 'calendar_event_links_calendar_event_id_index');
            $table->index('category', 'calendar_event_links_category_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('calendar_event_links');
    }
};