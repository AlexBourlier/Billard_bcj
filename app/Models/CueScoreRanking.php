<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function entries()
    {
        return $this->hasMany(CueScoreRankingEntry::class, 'cuescore_ranking_id');
    }

    public function fetches()
    {
        return $this->hasMany(CueScoreRankingFetch::class, 'cuescore_ranking_id');
    }


}
