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
        Schema::create('cuescore_ranking_fetches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cuescore_ranking_id')
                ->constrained('cuescore_rankings')
                ->onDelete('cascade')->index();

            $table->string('status', 30)->index();
            $table->timestamp('fetched_at')->nullable()->index();
            $table->unsignedSmallInteger('http_status')->nullable();
            $table->string('payload_hash', 64)->nullable();
            $table->unsignedInteger('records_count')->default(0);
            $table->string('error_code', 50)->nullable();
            $table->text('error_message')->nullable();
            $table->longText('raw_payload')->nullable();
            $table->boolean('is_active')->default(false)->index();

            $table->index(['cuescore_ranking_id', 'fetched_at']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cuescore_ranking_fetches');
    }
};
