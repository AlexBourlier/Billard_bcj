<?php

namespace App\Models;

use App\Domain\LicenseImport\Enums\BatchStatus;
use App\Domain\LicenseImport\Enums\TriggerType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LicenseImportBatch extends Model
{
    protected $fillable = [
        'source',
        'status',
        'is_active',
        'activated_at',
        'started_at',
        'finished_at',
        'trigger_type',
        'triggered_by_user_id',
        'triggered_by_label',
        'raw_rows_count',
        'valid_rows_count',
        'invalid_rows_count',
        'error_count',
        'warning_count',
        'source_fingerprint',
        'source_columns',
        'meta',
        'summary',
    ];

    protected $casts = [
        'status' => BatchStatus::class,
        'trigger_type' => TriggerType::class,
        'is_active' => 'boolean',
        'activated_at' => 'datetime',
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
        'source_columns' => 'array',
        'meta' => 'array',
        'summary' => 'array',
    ];

    public function snapshots(): HasMany
    {
        return $this->hasMany(LicenseImportSnapshot::class, 'import_batch_id');
    }

    public function issues(): HasMany
    {
        return $this->hasMany(LicenseImportIssue::class, 'import_batch_id');
    }

    public function triggeredByUser(): BelongsTo
    {
        return $this->belongsTo(AdminUser::class, 'triggered_by_user_id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeForSource(Builder $query, string $source): Builder
    {
        return $query->where('source', $source);
    }

    public function scopeWithStatus(Builder $query, BatchStatus $status): Builder
    {
        return $query->where('status', $status->value);
    }

    public function scopeLatestFirst(Builder $query): Builder
    {
        return $query->orderByDesc('started_at');
    }

    public function isTerminal(): bool
    {
        if (in_array($this->status, [
            BatchStatus::Activated,
            BatchStatus::Rejected,
            BatchStatus::Failed,
        ], true)) {
            return true;
        }

        return $this->status === BatchStatus::ValidatedComparative
            && $this->finished_at !== null
            && $this->is_active === false;
    }
}