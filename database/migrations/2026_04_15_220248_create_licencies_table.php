<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('licencies', function (Blueprint $table): void {
            $table->id();
            $table->string('licence');
            $table->string('nom');
            $table->string('prenom');
            $table->string('url');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('licencies');
    }
};