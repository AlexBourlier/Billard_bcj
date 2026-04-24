<?php

namespace Tests\Feature;

use App\Support\CacheKeys;
use DateTimeInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Mockery;
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
        $this->getJson('/api/v1/disciplines/blackball/rankings-preview')
            ->assertOk()
            ->assertJsonPath('meta.limit', 5);

        $this->getJson('/api/v1/disciplines/blackball/rankings-preview?limit=10')
            ->assertOk()
            ->assertJsonPath('meta.limit', 10);

        $this->assertTrue(
            Cache::has(CacheKeys::rankingsPreview('blackball', 5))
        );

        $this->assertTrue(
            Cache::has(CacheKeys::rankingsPreview('blackball', 10))
        );
    }

    public function test_public_home_uses_cache_remember_with_expected_key(): void
    {
        Cache::shouldReceive('remember')
            ->once()
            ->with(
                CacheKeys::publicHome(),
                Mockery::on(fn ($ttl) => $ttl instanceof DateTimeInterface),
                Mockery::type('Closure')
            )
            ->andReturnUsing(function ($key, $ttl, $callback) {
                return $callback();
            });

        $this->getJson('/api/v1/public/home')
            ->assertOk();
    }

    public function test_discipline_uses_cache_remember_with_expected_key(): void
    {
        Cache::shouldReceive('remember')
            ->once()
            ->with(
                CacheKeys::discipline('blackball'),
                Mockery::on(fn ($ttl) => $ttl instanceof DateTimeInterface),
                Mockery::type('Closure')
            )
            ->andReturnUsing(function ($key, $ttl, $callback) {
                return $callback();
            });

        $this->getJson('/api/v1/disciplines/blackball')
            ->assertOk();
    }

    public function test_rankings_preview_uses_cache_remember_with_limit_key(): void
    {
        Cache::shouldReceive('remember')
            ->once()
            ->with(
                CacheKeys::rankingsPreview('blackball', 10),
                Mockery::on(fn ($ttl) => $ttl instanceof DateTimeInterface),
                Mockery::type('Closure')
            )
            ->andReturnUsing(function ($key, $ttl, $callback) {
                return $callback();
            });

        $this->getJson('/api/v1/disciplines/blackball/rankings-preview?limit=10')
            ->assertOk()
            ->assertJsonPath('meta.limit', 10);
    }

    public function test_public_home_cache_callback_is_executed_only_once(): void
    {
        $calls = 0;

        Cache::flush();

        Cache::remember(
            CacheKeys::publicHome(),
            now()->addMinutes(10),
            function () use (&$calls) {
                $calls++;

                return [
                    'data' => [],
                    'meta' => [],
                    'links' => [],
                    'error' => null,
                ];
            }
        );

        Cache::remember(
            CacheKeys::publicHome(),
            now()->addMinutes(10),
            function () use (&$calls) {
                $calls++;

                return [
                    'data' => [],
                    'meta' => [],
                    'links' => [],
                    'error' => null,
                ];
            }
        );

        $this->assertSame(1, $calls);
    }

    public function test_public_home_cache_expires_and_callback_is_reexecuted(): void
    {
        Cache::flush();

        $calls = 0;

        // Premier appel → exécute le callback
        Cache::remember(
            CacheKeys::publicHome(),
            now()->addMinutes(10),
            function () use (&$calls) {
                $calls++;

                return ['ok' => true];
            }
        );

        // Deuxième appel immédiat → NE doit PAS exécuter
        Cache::remember(
            CacheKeys::publicHome(),
            now()->addMinutes(10),
            function () use (&$calls) {
                $calls++;

                return ['ok' => true];
            }
        );

        $this->assertSame(1, $calls);

        // On avance dans le temps (expiration)
        $this->travel(11)->minutes();

        // Troisième appel après expiration → DOIT réexécuter
        Cache::remember(
            CacheKeys::publicHome(),
            now()->addMinutes(10),
            function () use (&$calls) {
                $calls++;

                return ['ok' => true];
            }
        );

        $this->assertSame(2, $calls);
    }
}