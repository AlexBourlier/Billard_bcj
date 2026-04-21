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

    protected $casts = [
        'additional_data' => 'array',
        'points' => 'decimal:2',
    ];

    public function ranking()
    {
        return $this->belongsTo(CueScoreRanking::class, 'cuescore_ranking_id');
    }

    public function fetch()
    {
        return $this->belongsTo(CueScoreRankingFetch::class, 'cuescore_ranking_fetch_id');
    }

}
