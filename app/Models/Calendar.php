<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Calendar extends Model
{
    protected $table = 'calendars';

    protected $fillable = [
        'discipline',
        'scope',
        'name',
        'slug',
        'source_type',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $appends = [
        'display_name',
    ];

    public const DISCIPLINE_CARAMBOLE = 'carambole';
    public const DISCIPLINE_SNOOKER = 'snooker';
    public const DISCIPLINE_AMERICAIN = 'americain';
    public const DISCIPLINE_BLACKBALL = 'blackball';

    public const SCOPE_INTERNATIONAL = 'international';
    public const SCOPE_NATIONAL = 'national';
    public const SCOPE_REGIONAL = 'regional';
    public const SCOPE_DEPARTEMENTAL = 'departemental';

    public const SOURCE_MANUAL = 'manual';
    public const SOURCE_CUESCORE = 'cuescore';
    public const SOURCE_IMPORT = 'import';

    public static function disciplines(): array
    {
        return [
            self::DISCIPLINE_CARAMBOLE,
            self::DISCIPLINE_SNOOKER,
            self::DISCIPLINE_AMERICAIN,
            self::DISCIPLINE_BLACKBALL,
        ];
    }

    public static function scopes(): array
    {
        return [
            self::SCOPE_INTERNATIONAL,
            self::SCOPE_NATIONAL,
            self::SCOPE_REGIONAL,
            self::SCOPE_DEPARTEMENTAL,
        ];
    }

    public static function sourceTypes(): array
    {
        return [
            self::SOURCE_MANUAL,
            self::SOURCE_CUESCORE,
            self::SOURCE_IMPORT,
        ];
    }

    public static function isValidDiscipline(string $discipline): bool
    {
        return in_array($discipline, self::disciplines(), true);
    }

    public static function isValidScope(string $scope): bool
    {
        return in_array($scope, self::scopes(), true);
    }

    public static function isValidSourceType(?string $sourceType): bool
    {
        if ($sourceType === null) {
            return true;
        }

        return in_array($sourceType, self::sourceTypes(), true);
    }

    public function events(): HasMany
    {
        return $this->hasMany(CalendarEvent::class, 'calendar_id');
    }

    public function upcomingEvents(): HasMany
    {
        return $this->hasMany(CalendarEvent::class, 'calendar_id')
            ->where('date_fin', '>=', now())
            ->orderBy('date_debut');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive(Builder $query): Builder
    {
        return $query->where('is_active', false);
    }

    public function scopeByDiscipline(Builder $query, string $discipline): Builder
    {
        return $query->where('discipline', $discipline);
    }

    public function scopeByScope(Builder $query, string $scope): Builder
    {
        return $query->where('scope', $scope);
    }

    public function scopeBySlug(Builder $query, string $slug): Builder
    {
        return $query->where('slug', $slug);
    }

    public function scopeBySourceType(Builder $query, string $sourceType): Builder
    {
        return $query->where('source_type', $sourceType);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query
            ->orderBy('discipline')
            ->orderBy('scope');
    }

    public function scopeWithUpcomingEvents(Builder $query): Builder
    {
        return $query->with(['upcomingEvents.links']);
    }

    public function getDisplayNameAttribute(): string
    {
        if (!empty($this->name)) {
            return $this->name;
        }

        return ucfirst($this->discipline) . ' - ' . ucfirst($this->scope);
    }
}