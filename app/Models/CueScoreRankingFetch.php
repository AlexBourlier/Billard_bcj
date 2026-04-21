<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CueScoreRankingFetch extends Model
{
    use HasFactory;

    protected $table = 'cuescore_ranking_fetches';

    protected $fillable = [
        'cuescore_ranking_id',
        'status',
        'fetched_at',
        'http_status',
        'payload_hash',
        'records_count',
        'error_code',
        'error_message',
        'raw_payload',
        'is_active',
    ];

    // Note: 'fetched_at' est casté en datetime pour faciliter les requêtes basées sur les dates, et 'is_active' est casté en boolean pour une manipulation plus facile des valeurs booléennes.
    protected $casts = [
        'fetched_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    // Relations pour accéder au ranking associé à ce fetch et aux entrées de classement associées à ce fetch, ce qui permet de retracer l'origine des données de classement et d'analyser les différentes tentatives de récupération des données
    public function ranking()
    {
        return $this->belongsTo(CueScoreRanking::class, 'cuescore_ranking_id');
    }

    // Relation pour accéder aux entrées de classement associées à ce fetch, ce qui permet de retracer l'origine des données de classement et d'analyser les différentes tentatives de récupération des données
    public function entries()
    {
        return $this->hasMany(CueScoreRankingEntry::class, 'cuescore_ranking_fetch_id');
    }
}
