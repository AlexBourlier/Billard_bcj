<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LicenseImportSnapshot extends Model
{
    protected $fillable = [
        'import_batch_id',
        'row_index',
        'source_row_hash',
        'is_valid',
        'source_license_number',
        'license_number',
        'last_name',
        'first_name',
        'birth_date',
        'gender',
        'status',
        'category',
        'license_type',
        'season',
        'source_status_label',
        'source_category_label',
        'extra_data',
        'validation_flags',
    ];

    protected $casts = [
        'is_valid' => 'boolean',
        'birth_date' => 'date',
        'extra_data' => 'array',
        'validation_flags' => 'array',
    ];

    public function batch(): BelongsTo
    {
        return $this->belongsTo(LicenseImportBatch::class, 'import_batch_id');
    }

    public function scopeValid(Builder $query): Builder
    {
        return $query->where('is_valid', true);
    }

    public function scopeInvalid(Builder $query): Builder
    {
        return $query->where('is_valid', false);
    }

    public function scopeForBatch(Builder $query, int $batchId): Builder
    {
        return $query->where('import_batch_id', $batchId);
    }

    public function scopeForLicenseNumber(Builder $query, string $licenseNumber): Builder
    {
        return $query->where('license_number', $licenseNumber);
    }

    public function scopeOrderedForDisplay(Builder $query): Builder
    {
        return $query
            ->orderBy('last_name')
            ->orderBy('first_name');
    }
}