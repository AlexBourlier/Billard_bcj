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

    protected $casts = [
        'fetched_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function ranking()
    {
        return $this->belongsTo(CueScoreRanking::class, 'cuescore_ranking_id');
    }

    public function entries()
    {
        return $this->hasMany(CueScoreRankingEntry::class, 'cuescore_ranking_fetch_id');
    }
}
