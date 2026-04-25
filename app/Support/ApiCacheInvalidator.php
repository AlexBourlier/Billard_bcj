<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;

class ApiCacheInvalidator
{
    private const DISCIPLINES = [
        'blackball',
        'americain',
        'snooker',
        'carambole',
    ];

    private const RANKINGS_PREVIEW_LIMIT_MIN = 1;
    private const RANKINGS_PREVIEW_LIMIT_MAX = 10;

    public function publicHome(): void
    {
        Cache::forget(CacheKeys::publicHome());
    }

    public function discipline(string $discipline): void
    {
        Cache::forget(CacheKeys::discipline($discipline));
    }

    public function rankingsPreview(string $discipline): void
    {
        for (
            $limit = self::RANKINGS_PREVIEW_LIMIT_MIN;
            $limit <= self::RANKINGS_PREVIEW_LIMIT_MAX;
            $limit++
        ) {
            Cache::forget(CacheKeys::rankingsPreview($discipline, $limit));
        }
    }

    public function disciplinePage(string $discipline): void
    {
        $this->discipline($discipline);
        $this->rankingsPreview($discipline);
    }

    public function allPublic(): void
    {
        $this->publicHome();

        foreach (self::DISCIPLINES as $discipline) {
            $this->disciplinePage($discipline);
        }
    }
}