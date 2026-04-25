<?php

namespace Tests\Feature;

use App\Support\ApiCacheInvalidator;
use App\Support\CacheKeys;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class ApiCacheInvalidatorTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
    }

    public function test_it_invalidates_public_home_cache(): void
    {
        Cache::put(CacheKeys::publicHome(), ['cached' => true], now()->addMinutes(10));

        $this->assertTrue(Cache::has(CacheKeys::publicHome()));

        app(ApiCacheInvalidator::class)->publicHome();

        $this->assertFalse(Cache::has(CacheKeys::publicHome()));
    }

    public function test_it_invalidates_discipline_cache(): void
    {
        Cache::put(CacheKeys::discipline('blackball'), ['cached' => true], now()->addMinutes(10));

        $this->assertTrue(Cache::has(CacheKeys::discipline('blackball')));

        app(ApiCacheInvalidator::class)->discipline('blackball');

        $this->assertFalse(Cache::has(CacheKeys::discipline('blackball')));
    }

    public function test_it_invalidates_all_rankings_preview_limits_for_a_discipline(): void
    {
        for ($limit = 1; $limit <= 10; $limit++){
            Cache::put(
                CacheKeys::rankingsPreview('blackball', $limit),
                ['cached' => true],
                now()->addMinutes(5)
            );
        }

        app(ApiCacheInvalidator::class)->rankingsPreview('blackball');

        for ($limit = 1; $limit <= 10; $limit++) {
            $this->assertFalse(
                Cache::has(CacheKeys::rankingsPreview('blackball', $limit))
            );
        }
    }

    public function test_it_invalidates_discipline_page_cache(): void
    {
        Cache::put(CacheKeys::discipline('blackball'), ['cached' => true], now()->addMinutes(10));

        for ($limit = 1; $limit <= 10; $limit++){
            Cache::put(
                CacheKeys::rankingsPreview('blackball', $limit),
                ['cached' => true],
                now()->addMinutes(5)
            );
        }

        app(ApiCacheInvalidator::class)->disciplinePage('blackball');

        $this->assertFalse(Cache::has(CacheKeys::discipline('blackball')));

        for ($limit = 1; $limit <= 10; $limit++) {
            $this->assertFalse(
                Cache::has(CacheKeys::rankingsPreview('blackball', $limit))
            );
        }
    }
}