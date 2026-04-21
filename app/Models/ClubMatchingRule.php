<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClubMatchingRule extends Model
{
    use HasFactory;
    protected $table = 'club_matching_rules';

    protected $fillable = [
        'club_reference_name',
        'matching_mode',
        'matching_value',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

}
