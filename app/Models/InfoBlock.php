<?php

namespace App\Models;

use App\Support\ApiCacheInvalidator;
use Illuminate\Database\Eloquent\Model;

class InfoBlock extends Model
{
    protected $table = 'info_blocks';

    protected $fillable = [
        'titre',
        'resume',
        'niveau',
        'lien',
        'date_debut',
        'date_fin',
        'actif',
        'ordre',
    ];

    protected $casts = [
        'actif' => 'boolean',
        'ordre' => 'integer',
        'date_debut' => 'date',
        'date_fin' => 'date',
    ];

    /**
     * Blocs affichables publiquement : actifs et dans leur fenetre de dates
     * (facultatives), les plus importants d'abord.
     */
    public function scopeVisible($query)
    {
        $today = now()->toDateString();

        return $query
            ->where('actif', true)
            ->where(fn ($q) => $q->whereNull('date_debut')->orWhere('date_debut', '<=', $today))
            ->where(fn ($q) => $q->whereNull('date_fin')->orWhere('date_fin', '>=', $today))
            ->orderBy('ordre')
            ->orderByDesc('id');
    }

    /**
     * Le bloc est affiche sur la page d'accueil (mise en cache) : toute ecriture
     * invalide ce cache pour un affichage immediat.
     */
    protected static function booted(): void
    {
        $forget = static fn () => app(ApiCacheInvalidator::class)->publicHome();

        static::saved($forget);
        static::deleted($forget);
    }
}
