<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Licencies extends Model
{
    protected $table = 'licencies';

    protected $guarded = [];

    public function playerMappings(): HasMany
    {
        return $this->hasMany(CueScorePlayerMapping::class, 'licencie_id');
    }
}