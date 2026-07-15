<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Licencies extends Model
{
    protected $table = 'licencies';

    protected $guarded = [];

    // La table licencies n'a pas de colonnes created_at / updated_at.
    public $timestamps = false;

    public function playerMappings(): HasMany
    {
        return $this->hasMany(CueScorePlayerMapping::class, 'licencie_id');
    }
}