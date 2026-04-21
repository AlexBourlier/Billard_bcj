<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

final class Licencies extends Model
{
    protected $table = 'licencies';

    protected $guarded = [];

    public function playerMappings()
    {
        return $this->hasMany(CueScorePlayerMapping::class, 'licencie_id');
    }
}