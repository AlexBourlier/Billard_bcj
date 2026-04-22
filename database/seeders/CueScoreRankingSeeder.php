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

            [
                'name' => 'US CDF 2025-2026',
                'cuescore_id' => 74350597,
                'url' => 'https://cuescore.com/ranking/US_CDF_2025+2026/74350597',
                'source_type' => 'ranking',
                'discipline' => 'americain',
                'scope' => 'national',
                'ranking_type' => 'individual',
                'team_category' => null,
                'season' => '2025-2026',
                'is_active' => true,
                'sort_order' => 5,
            ],

            [
                'name' => 'US Classement TN Masters 2025-2026', 
                'cuescore_id' => 66292048,
                'url' => 'https://cuescore.com/ranking/US_Classement+TN+Masters+2025+2026/66292048',
                'source_type' => 'ranking',
                'discipline' => 'americain',
                'scope' => 'national',
                'ranking_type' => 'individual',
                'team_category' => null,
                'season' => '2025-2026',
                'is_active' => true,
                'sort_order' => 6,
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

            // NATIONAL
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

            [
                'name' => 'Snooker Classement Féminin 2025-2026',
                'cuescore_id' => 67004704,
                'url' => 'https://cuescore.com/ranking/SNOOKER_Classement+féminin+2025%252F2026/67004704',
                'source_type' => 'ranking',
                'discipline' => 'snooker',
                'scope' => 'national',
                'ranking_type' => 'individual',
                'team_category' => null,
                'season' => '2025-2026',
                'is_active' => true,
                'sort_order' => 21,
            ],

            [
                'name' => 'Snooker Classement TN Masters 2025-2026', 
                'cuescore_id' => 67004686,
                'url' => 'https://cuescore.com/ranking/SNOOKER_Classement+TN+Masters+2025-2026/67004686',
                'source_type' => 'ranking',
                'discipline' => 'snooker',
                'scope' => 'national',
                'ranking_type' => 'individual',
                'team_category' => null,
                'season' => '2025-2026',
                'is_active' => true,
                'sort_order' => 22,
            ],

            [
                'name' => 'Snooker Classement national 2025-2026',
                'cuescore_id' => 67004695,
                'url' => 'https://cuescore.com/ranking/SNOOKER_Classement+national+2025%252F2026/67004695',
                'source_type' => 'ranking',
                'discipline' => 'snooker',
                'scope' => 'national',
                'ranking_type' => 'individual',
                'team_category' => null,
                'season' => '2025-2026',
                'is_active' => true,
                'sort_order' => 23,
            ],

            // DEPARTEMENTAL
            [
                'name' => 'CVL Snooker ZOuest',
                'cuescore_id' => 73214698,
                'url' => 'https://cuescore.com/tournament/CVL+Snooker+ZOuest/73214698#match-73214737',
                'source_type' => 'tournament',
                'discipline' => 'snooker',
                'scope' => 'départemental',
                'ranking_type' => 'individual',
                'team_category' => null,
                'season' => '2025-2026',
                'is_active' => true,
                'sort_order' => 24,
            ],

            /*
            |--------------------------------------------------------------------------
            | BLACKBALL - REGIONAL
            |--------------------------------------------------------------------------
            */

            // NATIONAL
            [
                'name' => 'FFB - Blackball - TN - Handi Billard - 2025-2026', 
                'cuescore_id' => 65726164,
                'url' => 'https://cuescore.com/ranking/FFB+-+Blackball+-+TN+-+Handi+Billard/65726164',
                'source_type' => 'ranking',
                'discipline' => 'blackball',
                'scope' => 'national',
                'ranking_type' => 'individual',
                'team_category' => null,
                'season' => '2025-2026',
                'is_active' => true,
                'sort_order' => 25,
            ],

            [
                'name' => 'FFB - Blackball - TN - Master - 2025-2026', 
                'cuescore_id' => 65726161,
                'url' => 'https://cuescore.com/ranking/FFB+-+Blackball+-+TN+-+Master/65726161',
                'source_type' => 'ranking',
                'discipline' => 'blackball',
                'scope' => 'national',
                'ranking_type' => 'individual',
                'team_category' => null,
                'season' => '2025-2026',
                'is_active' => true,
                'sort_order' => 26,
            ],

            [
                'name' => 'FFB - Blackball - TN - Mixte National - 2025-2026',
                'cuescore_id' => 65726158,
                'url' => 'https://cuescore.com/ranking/FFB+-+Blackball+-+TN+-+Mixte+National/65726158',
                'source_type' => 'ranking',
                'discipline' => 'blackball',
                'scope' => 'national',
                'ranking_type' => 'individual',
                'team_category' => null,
                'season' => '2025-2026',
                'is_active' => true,
                'sort_order' => 27,
            ],

            [
                'name' => 'FFB - Blackball - TN - Vétéran - 2025-2026',
                'cuescore_id' => 65726155,
                'url' => 'https://cuescore.com/ranking/FFB+-+Blackball+-+TN+-+V%C3%A9t%C3%A9ran/65726155',
                'source_type' => 'ranking',
                'discipline' => 'blackball',
                'scope' => 'national',
                'ranking_type' => 'individual',
                'team_category' => null,
                'season' => '2025-2026',
                'is_active' => true,
                'sort_order' => 28,
            ],

            [
                'name' => 'FFB - Blackball - TN - Féminin - 2025-2026',
                'cuescore_id' => 65726152,
                'url' => 'https://cuescore.com/ranking/FFB+-+Blackball+-+TN+-+F%C3%A9minin/65726152', 
                'source_type' => 'ranking',
                'discipline' => 'blackball',
                'scope' => 'national',
                'ranking_type' => 'individual',
                'team_category' => null,
                'season' => '2025-2026',
                'is_active' => true,
                'sort_order' => 29,
            ],

            [
                'name' => 'FFB - Blackball - TN - Espoir - 2025-2026',
                'cuescore_id' => 65726149,
                'url' => 'https://cuescore.com/ranking/FFB+-+Blackball+-+TN+-+Espoir/65726149',
                'source_type' => 'ranking',
                'discipline' => 'blackball',
                'scope' => 'national',
                'ranking_type' => 'individual',
                'team_category' => null,
                'season' => '2025-2026',
                'is_active' => true,
                'sort_order' => 30,
            ],

            [
                'name' => 'FFB - Blackball - TN - Junior - 2025-2026',
                'cuescore_id' => 65726146,
                'url' => 'https://cuescore.com/ranking/FFB+-+Blackball+-+TN+-+Junior/65726146',
                'source_type' => 'ranking',
                'discipline' => 'blackball',
                'scope' => 'national',
                'ranking_type' => 'individual',
                'team_category' => null,
                'season' => '2025-2026',
                'is_active' => true,
                'sort_order' => 31,
            ],

            // REGIONAL
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
                'sort_order' => 32,
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
                'sort_order' => 33,
            ],

            [
                'name' => 'VETERAN 25-26',
                'cuescore_id' => 67097482,
                'url' => 'https://cuescore.com/ranking/VETERAN+25-26/67097482',
                'source_type' => 'ranking',
                'discipline' => 'blackball',
                'scope' => 'regional',
                'ranking_type' => 'individual',
                'team_category' => null,
                'season' => '2025-2026',
                'is_active' => true,
                'sort_order' => 34,
            ],

            [
                'name' => 'FEMININ 25-26',
                'cuescore_id' => 67097485,
                'url' => 'https://cuescore.com/ranking/FEMININ+25-26/67097485',
                'source_type' => 'ranking',
                'discipline' => 'blackball',
                'scope' => 'regional',
                'ranking_type' => 'individual',
                'team_category' => null,
                'season' => '2025-2026',
                'is_active' => true,
                'sort_order' => 35,
            ],

            [
                'name' => 'ESPOIR 25-26',
                'cuescore_id' => 67097488,
                'url' => 'https://cuescore.com/ranking/ESPOIR+25-26/67097488',
                'source_type' => 'ranking',
                'discipline' => 'blackball',
                'scope' => 'regional',
                'ranking_type' => 'individual',
                'team_category' => null,
                'season' => '2025-2026',
                'is_active' => true,
                'sort_order' => 36,
            ],

            [
                'name' => 'JUNIOR 25-26',
                'cuescore_id' => 67097494,
                'url' => 'https://cuescore.com/ranking/JUNIOR+25-26/67097494',
                'source_type' => 'ranking',
                'discipline' => 'blackball',
                'scope' => 'regional',
                'ranking_type' => 'individual',
                'team_category' => null,
                'season' => '2025-2026',
                'is_active' => true,
                'sort_order' => 37,
            ],

            [
                'name' => 'BENJAMIN 25-26',
                'cuescore_id' => 67097506,
                'url' => 'https://cuescore.com/ranking/BENJAMIN+25-26/67097506',
                'source_type' => 'ranking',
                'discipline' => 'blackball',
                'scope' => 'regional',
                'ranking_type' => 'individual',
                'team_category' => null,
                'season' => '2025-2026',
                'is_active' => true,
                'sort_order' => 38,
            ],

            [
                'name' => 'HANDI-DEBOUT 25-26',
                'cuescore_id' => 67097509,
                'url' => 'https://cuescore.com/ranking/HANDI-DEBOUT+25-26/67097509',
                'source_type' => 'ranking',
                'discipline' => 'blackball',
                'scope' => 'regional',
                'ranking_type' => 'individual',
                'team_category' => null,
                'season' => '2025-2026',
                'is_active' => true,
                'sort_order' => 39,
            ],

            [
                'name' => 'HANDI-FAUTEUIL 25-26',
                'cuescore_id' => 77825152,
                'url' => 'https://cuescore.com/ranking/HANDI-FAUTEUIL+25-26/77825152',
                'source_type' => 'ranking',
                'discipline' => 'blackball',
                'scope' => 'regional',
                'ranking_type' => 'individual',
                'team_category' => null,
                'season' => '2025-2026',
                'is_active' => true,
                'sort_order' => 40,
            ],

            // DEPARTEMENTAL
            [
                'name' => 'TRZ OUEST MIXTE 25-26',
                'cuescore_id' => 71389255,
                'url' => 'https://cuescore.com/ranking/TRZ+OUEST+MIXTE+25-26/71389255',
                'source_type' => 'ranking',
                'discipline' => 'blackball',
                'scope' => 'départemental',
                'ranking_type' => 'individual',
                'team_category' => null,
                'season' => '2025-2026',
                'is_active' => true,
                'sort_order' => 41,
            ],

            [
                'name' => 'TRZ OUEST FEMININ 25-26', 
                'cuescore_id' => 71389252,
                'url' => 'https://cuescore.com/ranking/TRZ+OUEST+FEMININ+25-26/71389252',
                'source_type' => 'ranking',
                'discipline' => 'blackball',
                'scope' => 'départemental',
                'ranking_type' => 'individual',
                'team_category' => null,
                'season' => '2025-2026',
                'is_active' => true,
                'sort_order' => 42,
            ],

            /*
            |--------------------------------------------------------------------------
            | BLACKBALL - EQUIPES
            |--------------------------------------------------------------------------
            */

            // NATIONAL
            [
                'name' => 'FFB - Blackball - Equipes - DN1 - 2025-2026',
                'cuescore_id' => 67490224,
                'url' => 'https://cuescore.com/tournament/FFB+-+Blackball+-+Equipes+-+DN1+-+2025-2026/67490224',
                'source_type' => 'tournament',
                'discipline' => 'blackball',
                'scope' => 'national',
                'ranking_type' => 'team',
                'team_category' => 'DN1',
                'season' => '2025-2026',
                'is_active' => true,
                'sort_order' => 43,
            ],

            [
                'name' => 'FFB - Blackball - Equipes - DN2 - 2025-2026',
                'cuescore_id' => 67490356,
                'url' => 'https://cuescore.com/tournament/FFB+-+Blackball+-+Equipes+-+DN2+-+2025-2026/67490356',
                'source_type' => 'tournament',
                'discipline' => 'blackball',
                'scope' => 'national',
                'ranking_type' => 'team',
                'team_category' => 'DN2',
                'season' => '2025-2026',
                'is_active' => true,
                'sort_order' => 44,
            ],

            [
                'name' => 'FFB - Blackball - Equipes - DN3 - 2025-2026',
                'cuescore_id' => 67490959,
                'url' => 'https://cuescore.com/tournament/FFB+-+Blackball+-+Equipes+-+DN3+-+2025-2026/67490959',
                'source_type' => 'tournament',
                'discipline' => 'blackball',
                'scope' => 'national',
                'ranking_type' => 'team',
                'team_category' => 'DN3',
                'season' => '2025-2026',
                'is_active' => true,
                'sort_order' => 45,
            ],

            // REGIONAL
            [
                'name' => 'BB_CVL ÉQUIPE DR1 25-26',
                'cuescore_id' => 67097593,
                'url' => 'https://cuescore.com/tournament/BB_CVL+ÉQUIPE+DR1+25-26/67097593',
                'source_type' => 'tournament',
                'discipline' => 'blackball',
                'scope' => 'regional',
                'ranking_type' => 'team',
                'team_category' => 'DR1',
                'season' => '2025-2026',
                'is_active' => true,
                'sort_order' => 46,
            ],

            [
                'name' => 'BB CVL ÉQUIPE DR2 25-26',
                'cuescore_id' => 67097608,
                'url' => 'https://cuescore.com/tournament/BB_CVL+ÉQUIPE+DR2+25-26/67097608',
                'source_type' => 'tournament',
                'discipline' => 'blackball',
                'scope' => 'regional',
                'ranking_type' => 'team',
                'team_category' => 'DR2',
                'season' => '2025-2026',
                'is_active' => true,
                'sort_order' => 47,
            ],

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
                'sort_order' => 48,
            ],

            // DEPARTEMENTAL
            [
                'name' => 'CVL ÉQUIPE DR4 ZONE OUEST 25-26',
                'cuescore_id' => 69856342,
                'url' => 'https://cuescore.com/tournament/CVL+ÉQUIPE+DR4+ZONE+OUEST+25-26/69856342#match-69864133',
                'source_type' => 'tournament',
                'discipline' => 'blackball',
                'scope' => 'départemental',
                'ranking_type' => 'team',
                'team_category' => 'DR4',
                'season' => '2025-2026',
                'is_active' => true,
                'sort_order' => 49,
            ],

        ];

        DB::table('cuescore_rankings')->delete(); // Optionnel : vide la table avant d'insérer les nouvelles données
        
        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE cuescore_rankings AUTO_INCREMENT = 1');
        }

        if ($driver === 'sqlite') {
            DB::statement("DELETE FROM sqlite_sequence WHERE name = 'cuescore_rankings'");
        }

        DB::table('cuescore_rankings')->insert($rankings);
    }
}