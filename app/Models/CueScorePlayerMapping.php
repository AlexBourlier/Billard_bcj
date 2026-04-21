<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    // Note: 'is_confirmed' est casté en boolean pour faciliter les requêtes et les manipulations de données
    protected $casts = [
        'confidence_score' => 'integer',
        'is_confirmed' => 'boolean',
    ];

    // Relation avec la table des licencies pour accéder aux informations du licencié associé à ce mapping
    public function licencie(): BelongsTo
    {
        return $this->belongsTo(Licencies::class, 'licencie_id');
    }


}
