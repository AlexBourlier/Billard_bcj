<?php

namespace Tests\Feature;

use App\Models\CueScoreRanking;
use App\Models\CueScoreRankingFetch;
use App\Services\CueScoreClubRankingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Mockery;
use Tests\TestCase;

class CueScoreRankingsPreviewApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_rankings_preview_groups_rankings_by_scope(): void
    {
        $ranking = CueScoreRanking::query()->create([
            'name' => 'Ranking national test',
            'cuescore_id' => '123456',
            'url' => 'https://cuescore.com/ranking/test',
            'source_type' => 'ranking',
            'discipline' => 'blackball',
            'scope' => 'national',
            'ranking_type' => 'individual',
            'team_category' => null,
            'season' => '2025-2026',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $fetch = CueScoreRankingFetch::query()->create([
            'cuescore_ranking_id' => $ranking->id,
            'status' => 'success',
            'is_active' => true,
        ]);

        $service = Mockery::mock(CueScoreClubRankingService::class);

        $service->shouldReceive('getActiveFetch')
            ->once()
            ->with(Mockery::type(CueScoreRanking::class))
            ->andReturn($fetch);

        $service->shouldReceive('buildIndividualRankingData')
            ->once()
            ->with(
                Mockery::type(CueScoreRanking::class),
                $fetch->id,
                5
            )
            ->andReturn([
                'data' => collect([
                    [
                        'rank_position' => 1,
                        'participant_name' => 'Player Test',
                        'points' => '100.00',
                    ],
                ]),
                'meta' => [],
            ]);

        $this->app->instance(CueScoreClubRankingService::class, $service);

        $response = $this->getJson('/api/v1/disciplines/blackball/rankings-preview');

        $response->assertOk()
            ->assertJsonPath('meta.discipline', 'blackball')
            ->assertJsonPath('meta.limit', 5)
            ->assertJsonPath('meta.rankings_supported', true)
            ->assertJsonPath('meta.count', 1)
            ->assertJsonStructure([
                'data' => [
                    'national' => [
                        '*' => [
                            'ranking',
                            'entries',
                            'meta',
                        ],
                    ],
                ],
                'meta',
                'links',
                'error',
            ]);
    }

    public function test_rankings_preview_passes_limit_to_service(): void
    {
        $ranking = CueScoreRanking::query()->create([
            'name' => 'Ranking regional test',
            'cuescore_id' => '654321',
            'url' => 'https://cuescore.com/ranking/test-regional',
            'source_type' => 'ranking',
            'discipline' => 'blackball',
            'scope' => 'regional',
            'ranking_type' => 'individual',
            'team_category' => null,
            'season' => '2025-2026',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $fetch = CueScoreRankingFetch::query()->create([
            'cuescore_ranking_id' => $ranking->id,
            'status' => 'success',
            'is_active' => true,
        ]);

        $service = Mockery::mock(CueScoreClubRankingService::class);

        $service->shouldReceive('getActiveFetch')
            ->once()
            ->andReturn($fetch);

        $service->shouldReceive('buildIndividualRankingData')
            ->once()
            ->with(
                Mockery::type(CueScoreRanking::class),
                $fetch->id,
                10
            )
            ->andReturn([
                'data' => collect([
                    [
                        'rank_position' => 1,
                        'participant_name' => 'Player Test',
                        'points' => '100.00',
                    ],
                ]),
                'meta' => [],
            ]);

        $this->app->instance(CueScoreClubRankingService::class, $service);

        $response = $this->getJson('/api/v1/disciplines/blackball/rankings-preview?limit=10');

        $response->assertOk()
            ->assertJsonPath('meta.limit', 10);
    }

    public function test_carambole_rankings_preview_is_not_supported(): void
    {
        $response = $this->getJson('/api/v1/disciplines/carambole/rankings-preview');

        $response->assertOk()
            ->assertJsonPath('data', null)
            ->assertJsonPath('meta.discipline', 'carambole')
            ->assertJsonPath('meta.rankings_supported', false)
            ->assertJsonPath('error', null);
    }
}