<?php

namespace App\Models;

use App\Support\ApiCacheInvalidator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    /**
     * Les articles alimentent la page d'accueil (article vedette) et les pages
     * discipline, toutes mises en cache : toute ecriture invalide ce cache pour
     * qu'une publication (ou un passage en brouillon) soit visible immediatement.
     */
    protected static function booted(): void
    {
        $forget = static fn () => app(ApiCacheInvalidator::class)->allPublic();

        static::saved($forget);
        static::deleted($forget);
    }

    /** Article enregistre mais non visible publiquement. */
    public const STATUS_DRAFT = 'draft';

    /** Article visible publiquement des que published_at est atteint. */
    public const STATUS_PUBLISHED = 'published';

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'thumbnail',
        'video',
        'discipline',
        'year',
        'favoris',
        'status',
        'published_at',
        'updated_by',
        'created_at',
    ];

    protected $attributes = [
        'favoris' => false,
        'status' => self::STATUS_PUBLISHED,
    ];

    protected $casts = [
        'favoris' => 'boolean',
        'published_at' => 'datetime',
    ];

    /**
     * Articles visibles publiquement : publies et dont la date de publication
     * est atteinte (une date future correspond a une publication programmee,
     * donc encore masquee). Les brouillons ne sont jamais exposes.
     *
     * Le cas published_at nul est tolere (article publie sans date explicite).
     */
    public function scopePublished($query)
    {
        return $query
            ->where('status', self::STATUS_PUBLISHED)
            ->where(function ($q) {
                $q->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getVideoAttribute($value)
    {
        if (str_contains($value, 'youtube.com/watch?v=')) {
            return preg_replace('/^.*watch\?v=([a-zA-Z0-9_-]+).*$/', 'https://www.youtube.com/embed/$1', $value);
        }

        if (str_contains($value, 'youtu.be/')) {
            return preg_replace('/^.*youtu\.be\/([a-zA-Z0-9_-]+).*$/', 'https://www.youtube.com/embed/$1', $value);
        }

        return $value;
    }

    public function disciplineName()
    {
        return [
            1 => 'blackball',
            2 => 'carambole',
            3 => 'snooker',
            4 => 'americain',
        ][$this->discipline] ?? 'club';
    }
}
