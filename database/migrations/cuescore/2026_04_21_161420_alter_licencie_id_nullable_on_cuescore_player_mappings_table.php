<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cuescore_player_mappings', function (Blueprint $table) {
            $table->dropForeign(['licencie_id']);
        });

        Schema::table('cuescore_player_mappings', function (Blueprint $table) {
            $table->unsignedBigInteger('licencie_id')->nullable()->change();
        });

        Schema::table('cuescore_player_mappings', function (Blueprint $table) {
            $table->foreign('licencie_id')
                ->references('id')
                ->on('licencies')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('cuescore_player_mappings', function (Blueprint $table) {
            $table->dropForeign(['licencie_id']);
        });

        Schema::table('cuescore_player_mappings', function (Blueprint $table) {
            $table->unsignedBigInteger('licencie_id')->nullable(false)->change();
        });

        Schema::table('cuescore_player_mappings', function (Blueprint $table) {
            $table->foreign('licencie_id')
                ->references('id')
                ->on('licencies')
                ->cascadeOnDelete();
        });
    }
};