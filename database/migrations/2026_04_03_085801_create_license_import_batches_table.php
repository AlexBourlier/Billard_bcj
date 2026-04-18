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
        Schema::create('license_import_batches', function (Blueprint $table) {
            $table->id();

            $table->string('source');
            $table->string('status');

            $table->boolean('is_active')->default(false);
            $table->timestamp('activated_at')->nullable();

            $table->timestamp('started_at');
            $table->timestamp('finished_at')->nullable();

            $table->string('trigger_type');
            $table->unsignedInteger('triggered_by_user_id')->nullable();
            $table->foreign('triggered_by_user_id')->references('id')->on('admin_users')->nullOnDelete();
            $table->string('triggered_by_label')->nullable();

            $table->unsignedInteger('raw_rows_count')->default(0);
            $table->unsignedInteger('valid_rows_count')->default(0);
            $table->unsignedInteger('invalid_rows_count')->default(0);
            $table->unsignedInteger('error_count')->default(0);
            $table->unsignedInteger('warning_count')->default(0);

            $table->string('source_fingerprint')->nullable();
            $table->json('source_columns')->nullable();
            $table->json('meta')->nullable();

            $table->text('summary')->nullable();

            $table->timestamps();

            $table->index('source');
            $table->index('status');
            $table->index('is_active');
            $table->index(['source', 'is_active']);
            $table->index('trigger_type');
            $table->index('started_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('license_import_batches');
    }
};
