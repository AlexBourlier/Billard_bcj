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
        Schema::create('club_matching_rules', function (Blueprint $table) {
            $table->id();
            $table->string('club_reference_name');
            $table->string('matching_mode', 30)->index();
            $table->string('matching_value');
            $table->boolean('is_active')->default(true)->index();

            $table->index(['is_active', 'matching_mode']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('club_matching_rules');
    }
};
