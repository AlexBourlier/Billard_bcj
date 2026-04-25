<?php

namespace Tests\Feature;

use App\Models\CueScoreRanking;
use App\Services\CueScore\CueScoreApiClient;
use App\Services\CueScore\CueScoreEntryParser;
use App\Services\CueScore\CueScoreRankingImporter;
use App\Support\CacheKeys;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Cache;
use Mockery;
use Tests\TestCase;

class ApiCacheWithCueScoreInvalidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_cache_is_invalidated_and_rankings_preview_is_warmed_after_successful_cuescore_import(): void
    {
        Cache::flush();

        $ranking = CueScoreRanking::query()->create([
            'name' => 'Ranking test',
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

        Cache::put(CacheKeys::discipline('blackball'), ['cached' => true], now()->addMinutes(10));
        Cache::put(CacheKeys::rankingsPreview('blackball', 5), ['cached' => true], now()->addMinutes(5));

        $this->assertTrue(Cache::has(CacheKeys::discipline('blackball')));
        $this->assertTrue(Cache::has(CacheKeys::rankingsPreview('blackball', 5)));

        $response = Mockery::mock(Response::class);
        $response->shouldReceive('json')->andReturn([
            'participants' => [],
        ]);
        $response->shouldReceive('successful')->andReturn(true);
        $response->shouldReceive('status')->andReturn(200);

        $client = Mockery::mock(CueScoreApiClient::class);
        $client->shouldReceive('fetchRanking')
            ->twice()
            ->with('123456')
            ->andReturn($response);

        $parser = Mockery::mock(CueScoreEntryParser::class);
        $parser->shouldReceive('parseRankingParticipants')
            ->once()
            ->andReturn([]);

        $this->app->instance(CueScoreApiClient::class, $client);
        $this->app->instance(CueScoreEntryParser::class, $parser);

        app(CueScoreRankingImporter::class)->import($ranking);

        $this->assertFalse(Cache::has(CacheKeys::discipline('blackball')));

        $this->assertTrue(
            Cache::has(CacheKeys::rankingsPreview('blackball', 5))
        );
    }
}