<?php

namespace App\Models;

use App\Domain\LicenseImport\Enums\IssueSeverity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LicenseImportIssue extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = [
        'import_batch_id',
        'severity',
        'code',
        'message',
        'context',
        'row_index',
    ];

    protected $casts = [
        'severity' => IssueSeverity::class,
        'context' => 'array',
        'created_at' => 'datetime',
    ];

    public function batch(): BelongsTo
    {
        return $this->belongsTo(LicenseImportBatch::class, 'import_batch_id');
    }

    public function scopeForBatch(Builder $query, int $batchId): Builder
    {
        return $query->where('import_batch_id', $batchId);
    }

    public function scopeWithSeverity(Builder $query, IssueSeverity $severity): Builder
    {
        return $query->where('severity', $severity->value);
    }

    public function scopeErrors(Builder $query): Builder
    {
        return $query->where('severity', IssueSeverity::Error->value);
    }

    public function scopeWarnings(Builder $query): Builder
    {
        return $query->where('severity', IssueSeverity::Warning->value);
    }
}