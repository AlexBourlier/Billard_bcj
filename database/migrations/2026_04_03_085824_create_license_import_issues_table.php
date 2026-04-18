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
        Schema::create('license_import_issues', function (Blueprint $table) {
            $table->id();

            $table->foreignId('import_batch_id')
                ->constrained('license_import_batches')
                ->cascadeOnDelete();

            $table->string('severity');
            $table->string('code');

            $table->text('message');

            $table->json('context')->nullable();

            $table->unsignedInteger('row_index')->nullable();

            $table->timestamp('created_at')->useCurrent();

            $table->index('import_batch_id');
            $table->index('severity');
            $table->index('code');
            $table->index(['import_batch_id', 'severity']);
            $table->index(['import_batch_id', 'code']);
            $table->index('row_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('license_import_issues');
    }
};
