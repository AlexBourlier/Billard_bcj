<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CalendarEventLink extends Model
{
    protected $table = 'calendar_event_links';

    protected $fillable = [
        'calendar_event_id',
        'category',
        'label',
        'url',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    protected $appends = [
        'category_label',
    ];

    public const CATEGORY_TOP_LIGUE = 'top_ligue';
    public const CATEGORY_MASTER = 'master';
    public const CATEGORY_MIXTE = 'mixte';
    public const CATEGORY_FEMININ = 'feminin';
    public const CATEGORY_U15 = 'u15';
    public const CATEGORY_U18 = 'u18';
    public const CATEGORY_U23 = 'u23';
    public const CATEGORY_HANDI_DEBOUT = 'handi_debout';
    public const CATEGORY_HANDI_FAUTEUIL = 'handi_fauteuil';
    public const CATEGORY_VETERAN = 'veteran';

    public static function categories(): array
    {
        return [
            self::CATEGORY_TOP_LIGUE,
            self::CATEGORY_MASTER,
            self::CATEGORY_MIXTE,
            self::CATEGORY_FEMININ,
            self::CATEGORY_U15,
            self::CATEGORY_U18,
            self::CATEGORY_U23,
            self::CATEGORY_HANDI_DEBOUT,
            self::CATEGORY_HANDI_FAUTEUIL,
            self::CATEGORY_VETERAN,
        ];
    }

    public static function isValidCategory(string $category): bool
    {
        return in_array($category, self::categories(), true);
    }

    public static function getCategoryLabel(string $category): string
    {
        return match ($category) {
            self::CATEGORY_TOP_LIGUE => 'Top Ligue',
            self::CATEGORY_MASTER => 'Master',
            self::CATEGORY_MIXTE => 'Mixte',
            self::CATEGORY_FEMININ => 'Féminin',
            self::CATEGORY_U15 => 'U15',
            self::CATEGORY_U18 => 'U18',
            self::CATEGORY_U23 => 'U23',
            self::CATEGORY_HANDI_DEBOUT => 'Handi Debout',
            self::CATEGORY_HANDI_FAUTEUIL => 'Handi Fauteuil',
            self::CATEGORY_VETERAN => 'Vétéran',
            default => ucfirst(str_replace('_', ' ', $category)),
        };
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(CalendarEvent::class, 'calendar_event_id');
    }

    public function scopeByCategory(Builder $query, string $category): Builder
    {
        return $query->where('category', $category);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query
            ->orderByRaw('sort_order IS NULL, sort_order ASC')
            ->orderBy('id');
    }

    public function getCategoryLabelAttribute(): string
    {
        return self::getCategoryLabel($this->category);
    }
}