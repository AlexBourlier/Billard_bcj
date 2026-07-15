<?php

namespace Tests\Feature\CueScore;

use App\Models\CueScoreRanking;
use Database\Seeders\CueScoreRankingSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CueScoreRankingSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeder_is_idempotent_and_preserves_category(): void
    {
        $this->seed(CueScoreRankingSeeder::class);

        $countAfterFirst = CueScoreRanking::count();
        $this->assertGreaterThan(0, $countAfterFirst);

        // Simule une edition faite via le CRUD admin (categorie posee a la main).
        $ranking = CueScoreRanking::query()->first();
        $ranking->update(['category' => 'Categorie manuelle']);

        // Re-seed : ne doit ni dupliquer, ni ecraser la categorie.
        $this->seed(CueScoreRankingSeeder::class);

        $this->assertSame($countAfterFirst, CueScoreRanking::count());
        $this->assertSame('Categorie manuelle', $ranking->fresh()->category);
    }
}
