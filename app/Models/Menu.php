<?php

namespace App\Models;

use App\Support\ApiCacheInvalidator;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = [
        'nom',
        'image',
        'actif',
    ];

    protected $casts = [
        'actif' => 'boolean',
    ];

    /**
     * Les menus alimentent la navigation du site (dont la page d'accueil mise en
     * cache) : toute activation/desactivation invalide ce cache pour un effet
     * immediat cote public.
     */
    protected static function booted(): void
    {
        $forget = static fn () => app(ApiCacheInvalidator::class)->publicHome();

        static::saved($forget);
        static::deleted($forget);
    }
}
