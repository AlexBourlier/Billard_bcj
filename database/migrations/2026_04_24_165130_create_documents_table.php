<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('title', 100);
            $table->string('file', 255);
            $table->integer('discipline')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};