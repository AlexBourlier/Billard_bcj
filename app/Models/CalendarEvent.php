<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CalendarEvent extends Model
{
    protected $table = 'calendar_events';

    protected $fillable = [
        'calendar_id',
        'external_id',
        'date_debut',
        'date_fin',
        'date_limite',
        'titre',
        'lieu',
        'club',
        'url',
        'status',
        'source_payload',
    ];

    protected $casts = [
        'date_debut' => 'datetime',
        'date_fin' => 'datetime',
        'date_limite' => 'datetime',
        'source_payload' => 'array',
    ];

    protected $appends = [
        'is_upcoming',
        'is_past',
        'has_registration_deadline',
    ];

    public function calendar(): BelongsTo
    {
        return $this->belongsTo(Calendar::class, 'calendar_id');
    }

    public function links(): HasMany
    {
        return $this->hasMany(CalendarEventLink::class, 'calendar_event_id')->ordered();
    }

    public function scopeUpcoming(Builder $query): Builder
    {
        return $query
            ->where('date_fin', '>=', now())
            ->orderBy('date_debut');
    }

    public function scopePast(Builder $query): Builder
    {
        return $query
            ->where('date_fin', '<', now())
            ->orderByDesc('date_debut');
    }

    public function scopeByCalendar(Builder $query, int $calendarId): Builder
    {
        return $query->where('calendar_id', $calendarId);
    }

    public function scopeByStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    public function scopeBetweenDates(Builder $query, ?string $from = null, ?string $to = null): Builder
    {
        return $query
            ->when($from, fn (Builder $q) => $q->where('date_debut', '>=', $from))
            ->when($to, fn (Builder $q) => $q->where('date_debut', '<=', $to));
    }

    public function scopeForDiscipline(Builder $query, string $discipline): Builder
    {
        return $query->whereHas('calendar', function (Builder $q) use ($discipline) {
            $q->where('discipline', $discipline);
        });
    }

    public function scopeForScope(Builder $query, string $scope): Builder
    {
        return $query->whereHas('calendar', function (Builder $q) use ($scope) {
            $q->where('scope', $scope);
        });
    }

    public function scopeWithCalendarAndLinks(Builder $query): Builder
    {
        return $query->with([
            'calendar',
            'links',
        ]);
    }

    public function getIsUpcomingAttribute(): bool
    {
        return $this->date_fin !== null
            && $this->date_fin->greaterThanOrEqualTo(now());
    }

    public function getIsPastAttribute(): bool
    {
        return $this->date_fin !== null
            && $this->date_fin->lt(now());
    }

    public function getHasRegistrationDeadlineAttribute(): bool
    {
        return $this->date_limite !== null;
    }
}