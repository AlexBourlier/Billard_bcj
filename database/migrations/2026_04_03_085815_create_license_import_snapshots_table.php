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
        Schema::create('license_import_snapshots', function (Blueprint $table) {
            $table->id();

            $table->foreignId('import_batch_id')
                ->constrained('license_import_batches')
                ->cascadeOnDelete();

            $table->unsignedInteger('row_index')->nullable();
            $table->string('source_row_hash')->nullable();
            $table->boolean('is_valid')->default(true);

            $table->string('source_license_number')->nullable();
            $table->string('license_number')->nullable();

            $table->string('last_name')->nullable();
            $table->string('first_name')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('gender')->nullable();
            $table->string('status')->nullable();
            $table->string('category')->nullable();
            $table->string('license_type')->nullable();
            $table->string('season')->nullable();

            $table->string('source_status_label')->nullable();
            $table->string('source_category_label')->nullable();

            $table->json('extra_data')->nullable();
            $table->json('validation_flags')->nullable();

            $table->timestamps();

            $table->index('import_batch_id');
            $table->index('license_number');
            $table->index(['import_batch_id', 'license_number']);
            $table->index(['import_batch_id', 'last_name']);
            $table->index(['import_batch_id', 'first_name']);
            $table->index('is_valid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('license_import_snapshots');
    }
};
