<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CueScorePlayerMapping extends Model
{
    use HasFactory;

    protected $table = 'cuescore_player_mappings';

    protected $fillable = [
        'cuescore_participant_id',
        'cuescore_name',
        'cuescore_url',
        'licencie_id',
        'matching_method',
        'confidence_score',
        'is_confirmed',
        'notes',
    ];

    protected $casts = [
        'is_confirmed' => 'boolean',
    ];

    public function licencie()
    {
        return $this->belongsTo(Licencies::class, 'licencie_id');
    }


}
