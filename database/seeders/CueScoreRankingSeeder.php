<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CueScoreRankingSeeder extends Seeder
{
    public function run(): void
    {
        $rankings = [

            /*
            |--------------------------------------------------------------------------
            | AMERICAIN
            |--------------------------------------------------------------------------
            */

            // NATIONAL
            [
                'name' => 'US Demi Finale N1 SUD 2025-2026',
                'cuescore_id' => 79941976,
                'url' => 'https://cuescore.com/ranking/US_Demi+Finale+N1+SUD_2025-2026/79941976',
                'source_type' => 'ranking',
                'discipline' => 'americain',
                'scope' => 'national',
                'ranking_type' => 'individual',
                'team_category' => null,
                'season' => '2025-2026',
                'is_active' => true,
                'sort_order' => 1,
            ],

            [
                'name' => 'US Demi Finale N1 NORD 2025-2026',
                'cuescore_id' => 79941973,
                'url' => 'https://cuescore.com/ranking/US_Demi+Finale+N1+NORD_2025-2026/79941973',
                'source_type' => 'ranking',
                'discipline' => 'americain',
                'scope' => 'national',
                'ranking_type' => 'individual',
                'team_category' => null,
                'season' => '2025-2026',
                'is_active' => true,
                'sort_order' => 2,
            ],

            [
                'name' => 'US Finale France U17 2025-2026',
                'cuescore_id' => 79880440,
                'url' => 'https://cuescore.com/ranking/US_finale+de+France+U17+2025-2026/79880440',
                'source_type' => 'ranking',
                'discipline' => 'americain',
                'scope' => 'national',
                'ranking_type' => 'individual',
                'team_category' => null,
                'season' => '2025-2026',
                'is_active' => true,
                'sort_order' => 3,
            ],

            [
                'name' => 'US Classement TN N1 2025-2026',
                'cuescore_id' => 66292051,
                'url' => 'https://cuescore.com/ranking/US_Classement+TN+N1++2025-2026/66292051',
                'source_type' => 'ranking',
                'discipline' => 'americain',
                'scope' => 'national',
                'ranking_type' => 'individual',
                'team_category' => null,
                'season' => '2025-2026',
                'is_active' => true,
                'sort_order' => 4,
            ],

            // REGIONAL
            [
                'name' => 'LBCVL Finale Ligue Americain 25-26',
                'cuescore_id' => 76092814,
                'url' => 'https://cuescore.com/ranking/LBCVL+FINALE+LIGUE+AMERICAIN+25-26/76092814',
                'source_type' => 'ranking',
                'discipline' => 'americain',
                'scope' => 'regional',
                'ranking_type' => 'individual',
                'team_category' => null,
                'season' => '2025-2026',
                'is_active' => true,
                'sort_order' => 10,
            ],

            /*
            |--------------------------------------------------------------------------
            | SNOOKER
            |--------------------------------------------------------------------------
            */

            [
                'name' => 'Snooker Classement Seniors 2025-2026',
                'cuescore_id' => 67004710,
                'url' => 'https://cuescore.com/ranking/SNOOKER_Classement+Seniors+2025%252F2026/67004710',
                'source_type' => 'ranking',
                'discipline' => 'snooker',
                'scope' => 'national',
                'ranking_type' => 'individual',
                'team_category' => null,
                'season' => '2025-2026',
                'is_active' => true,
                'sort_order' => 20,
            ],

            /*
            |--------------------------------------------------------------------------
            | BLACKBALL - REGIONAL
            |--------------------------------------------------------------------------
            */

            [
                'name' => 'TOP LIGUE 25-26',
                'cuescore_id' => 67097476,
                'url' => 'https://cuescore.com/ranking/TOP+LIGUE+25-26/67097476',
                'source_type' => 'ranking',
                'discipline' => 'blackball',
                'scope' => 'regional',
                'ranking_type' => 'individual',
                'team_category' => null,
                'season' => '2025-2026',
                'is_active' => true,
                'sort_order' => 30,
            ],

            [
                'name' => 'MIXTE 25-26',
                'cuescore_id' => 67097479,
                'url' => 'https://cuescore.com/ranking/MIXTE+25-26/67097479',
                'source_type' => 'ranking',
                'discipline' => 'blackball',
                'scope' => 'regional',
                'ranking_type' => 'individual',
                'team_category' => null,
                'season' => '2025-2026',
                'is_active' => true,
                'sort_order' => 31,
            ],

            /*
            |--------------------------------------------------------------------------
            | BLACKBALL - EQUIPES
            |--------------------------------------------------------------------------
            */

            [
                'name' => 'BB CVL ÉQUIPE DR3 25-26',
                'cuescore_id' => 67097614,
                'url' => 'https://cuescore.com/tournament/BB_CVL+ÉQUIPE+DR3+25-26/67097614',
                'source_type' => 'tournament',
                'discipline' => 'blackball',
                'scope' => 'regional',
                'ranking_type' => 'team',
                'team_category' => 'DR3',
                'season' => '2025-2026',
                'is_active' => true,
                'sort_order' => 40,
            ],

        ];

        DB::table('cuescore_rankings')->truncate(); // Optionnel : vide la table avant d'insérer les nouvelles données
        DB::table('cuescore_rankings')->insert($rankings);
    }
}