<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CueScoreRankingEntry extends Model
{
    use HasFactory;

    protected $table = 'cuescore_ranking_entries';

    protected $fillable = [
        'cuescore_ranking_id',
        'cuescore_ranking_fetch_id',
        'entry_type',
        'rank_position',
        'participant_name',
        'participant_external_id',
        'participant_url',
        'team_name',
        'team_external_id',
        'team_url',
        'points',
        'played',
        'wins',
        'losses',
        'ties',
        'additional_data',
    ];

    // Note: 'additional_data' est casté en array pour faciliter l'accès aux données supplémentaires stockées au format JSON, et 'points' est casté en decimal pour garantir une manipulation correcte des valeurs numériques avec deux décimales
    protected $casts = [
        'additional_data' => 'array',
        'points' => 'decimal:2',
    ];

    // Relations pour accéder au ranking et au fetch associés à cette entrée de classement
    public function ranking()
    {
        return $this->belongsTo(CueScoreRanking::class, 'cuescore_ranking_id');
    }

    // Relation pour accéder au fetch associé à cette entrée de classement, ce qui permet de retracer l'origine des données de classement et d'analyser les différentes tentatives de récupération des données
    public function fetch()
    {
        return $this->belongsTo(CueScoreRankingFetch::class, 'cuescore_ranking_fetch_id');
    }

}
