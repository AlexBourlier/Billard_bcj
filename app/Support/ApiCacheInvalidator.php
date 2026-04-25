<?php

namespace App\Support;

use App\Support\CacheKeys;
use Illuminate\Support\Facades\Cache;

class ApiCacheInvalidator
{
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
        for ($limit = 1; $limit <= 10; $limit++) {
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

        foreach (['blackball', 'americain', 'snooker', 'carambole'] as $discipline) {
            $this->disciplinePage($discipline);
        }
    }
}