<?php

namespace App\Models;

use App\Support\ApiCacheInvalidator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Partenaire extends Model
{
    use HasFactory;

    /**
     * Les partenaires sont affiches sur la page d'accueil (mise en cache) :
     * toute creation / modification / suppression invalide ce cache pour que
     * les changements faits en admin soient visibles immediatement.
     */
    protected static function booted(): void
    {
        $forget = static fn () => app(ApiCacheInvalidator::class)->publicHome();

        static::saved($forget);
        static::deleted($forget);
    }

    protected $fillable = [
        'titre',
        'img',
        'url',
        'ordre',
        'actif',
        'alt',
        'date_debut',
        'date_fin',
    ];

    protected $casts = [
        'actif' => 'boolean',
        'ordre' => 'integer',
        'date_debut' => 'date',
        'date_fin' => 'date',
    ];

    /**
     * Partenaires visibles publiquement : actifs et dans leur fenetre de
     * partenariat (dates facultatives), tries par ordre d'affichage.
     */
    public function scopeVisible($query)
    {
        $today = now()->toDateString();

        return $query
            ->where('actif', true)
            ->where(fn ($q) => $q->whereNull('date_debut')->orWhere('date_debut', '<=', $today))
            ->where(fn ($q) => $q->whereNull('date_fin')->orWhere('date_fin', '>=', $today))
            ->orderBy('ordre')
            ->orderBy('id');
    }
}
