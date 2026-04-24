<?php

namespace Tests\Feature;

use App\Support\CacheKeys;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class ApiCacheTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
    }

    public function test_public_home_response_is_cached(): void
    {
        // $this->withoutExceptionHandling();

        $response = $this->getJson('/api/v1/public/home');

        $response->assertOk()
            ->assertJsonStructure([
                'data',
                'meta',
                'links',
                'error',
            ]);

        $this->assertTrue(Cache::has(CacheKeys::publicHome()));
    }

    public function test_discipline_response_is_cached(): void
    {
        $this->withoutExceptionHandling();
        
        $response = $this->getJson('/api/v1/disciplines/blackball');

        $response->assertOk()
            ->assertJsonStructure([
                'data',
                'meta',
                'links',
                'error',
            ]);

        $this->assertTrue(
            Cache::has(CacheKeys::discipline('blackball'))
        );
    }

    public function test_rankings_preview_is_cached_with_default_limit(): void
    {
        $response = $this->getJson('/api/v1/disciplines/blackball/rankings-preview');

        $response->assertOk()
            ->assertJsonPath('meta.limit', 5);

        $this->assertTrue(
            Cache::has(CacheKeys::rankingsPreview('blackball', 5))
        );
    }

    public function test_rankings_preview_cache_differs_by_limit(): void
    {
        // appel limit par défaut (5)
        $this->getJson('/api/v1/disciplines/blackball/rankings-preview')
            ->assertOk();

        // appel avec limit 10
        $this->getJson('/api/v1/disciplines/blackball/rankings-preview?limit=10')
            ->assertOk();

        $this->assertTrue(
            Cache::has(CacheKeys::rankingsPreview('blackball', 5))
        );

        $this->assertTrue(
            Cache::has(CacheKeys::rankingsPreview('blackball', 10))
        );
    }
}