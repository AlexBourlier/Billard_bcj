<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CueScoreRanking extends Model
{
    use HasFactory;

    protected $table = 'cuescore_rankings';

    protected $fillable = [
        'name',
        'cuescore_id',
        'url',
        'source_type',
        'discipline',
        'scope',
        'ranking_type',
        'team_category',
        'season',
        'is_active',
        'sort_order',
    ];

    // Note: 'is_active' est déjà défini dans $fillable, mais il est également important de le caster en boolean pour s'assurer que les valeurs sont correctement interprétées lors de l'accès à cet attribut.
    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    // Relations avec les entrées et les fetches associées à ce ranking
    public function entries(): HasMany
    {
        return $this->hasMany(CueScoreRankingEntry::class, 'cuescore_ranking_id');
    }

    // Relation avec les fetches associées à ce ranking pour accéder aux différentes tentatives de récupération des données de classement
    public function fetches(): HasMany
    {
        return $this->hasMany(CueScoreRankingFetch::class, 'cuescore_ranking_id');
    }


}
