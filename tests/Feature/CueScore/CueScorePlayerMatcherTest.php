<?php

namespace Tests\Feature\CueScore;

use App\Models\CueScorePlayerMapping;
use App\Models\CueScoreRanking;
use App\Models\CueScoreRankingEntry;
use App\Models\CueScoreRankingFetch;
use App\Models\Licencies;
use App\Services\CueScore\CueScorePlayerMatcher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CueScorePlayerMatcherTest extends TestCase
{
    use RefreshDatabase;

    private function makeFetch(): CueScoreRankingFetch
    {
        $ranking = CueScoreRanking::create([
            'name' => 'Test',
            'cuescore_id' => '123',
            'url' => 'https://cuescore.com/ranking/test/123',
            'source_type' => 'ranking',
            'discipline' => 'blackball',
            'scope' => 'national',
            'ranking_type' => 'individual',
            'team_category' => null,
            'season' => '2025-2026',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        return CueScoreRankingFetch::create([
            'cuescore_ranking_id' => $ranking->id,
            'status' => 'success',
            'fetched_at' => now(),
            'is_active' => true,
            'records_count' => 0,
        ]);
    }

    private function addEntry(CueScoreRankingFetch $fetch, string $name, string $externalId): void
    {
        CueScoreRankingEntry::create([
            'cuescore_ranking_id' => $fetch->cuescore_ranking_id,
            'cuescore_ranking_fetch_id' => $fetch->id,
            'entry_type' => 'player',
            'participant_name' => $name,
            'participant_external_id' => $externalId,
        ]);
    }

    public function test_exact_match_is_linked_and_confirmed_and_unmatched_stays_null(): void
    {
        $jean = Licencies::create(['licence' => 'L1', 'nom' => 'Dupont', 'prenom' => 'Jean', 'url' => null]);
        Licencies::create(['licence' => 'L2', 'nom' => 'Curie', 'prenom' => 'Marie', 'url' => null]);

        $fetch = $this->makeFetch();
        // Accents/casse differents mais normalisation identique -> match exact.
        $this->addEntry($fetch, 'JEAN Dupont', 'E1');
        $this->addEntry($fetch, 'Zzzz Qqqq', 'E2');

        $count = app(CueScorePlayerMatcher::class)->matchEntriesForFetch($fetch->id);

        $this->assertSame(2, $count);

        $m1 = CueScorePlayerMapping::where('cuescore_participant_id', 'E1')->firstOrFail();
        $this->assertSame($jean->id, $m1->licencie_id);
        $this->assertSame(100, $m1->confidence_score);
        $this->assertTrue((bool) $m1->is_confirmed);
        $this->assertSame('exact_normalized', $m1->matching_method);

        $m2 = CueScorePlayerMapping::where('cuescore_participant_id', 'E2')->firstOrFail();
        $this->assertNull($m2->licencie_id);
        $this->assertFalse((bool) $m2->is_confirmed);
    }

    public function test_licencies_are_loaded_once_per_fetch(): void
    {
        foreach (range(1, 5) as $i) {
            Licencies::create(['licence' => "L$i", 'nom' => "Nom$i", 'prenom' => "Prenom$i", 'url' => null]);
        }

        $fetch = $this->makeFetch();
        foreach (range(1, 3) as $i) {
            $this->addEntry($fetch, "Joueur $i", "E$i");
        }

        DB::enableQueryLog();
        app(CueScorePlayerMatcher::class)->matchEntriesForFetch($fetch->id);

        $licencieLoads = collect(DB::getQueryLog())
            ->filter(fn ($q) => str_contains($q['query'], '"licencies"'))
            ->count();

        // Refactor perf : la table licencies est chargee une seule fois pour
        // tout le fetch, quel que soit le nombre d'entrees.
        $this->assertSame(1, $licencieLoads);
    }
}
