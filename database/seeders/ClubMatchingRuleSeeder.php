<?php

namespace Database\Seeders;

use App\Models\ClubMatchingRule;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClubMatchingRuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('club_matching_rules')->insert([
            'club_reference_name' => 'Billard Club de Joué-lès-Tours',
            'matching_mode' => 'contains',
            'matching_value' => 'joue',
            'is_active' => true,
            'created_at' => now()->subDays(10),
            'updated_at' => now()->subDays(10),
        ]);

        DB::table('club_matching_rules')->insert([
            'club_reference_name' => 'Billard Club de Joué-lès-Tours',
            'matching_mode' => 'contains',
            'matching_value' => 'bcj',
            'is_active' => true,
            'created_at' => now()->subDays(5),
            'updated_at' => now()->subDays(5),
        ]);

        DB::table('club_matching_rules')->insert([
            'club_reference_name' => 'Billard Club de Joué-lès-Tours',
            'matching_mode' => 'contains',
            'matching_value' => 'joue les tours',
            'is_active' => true,
            'created_at' => now()->subDays(2),
            'updated_at' => now()->subDays(2),
        ]);
    }
}
