<?php

namespace App\Support;

use App\Services\CueScore\CueScoreRankingsPreviewBuilder;
use Illuminate\Support\Facades\Cache;

class ApiCacheWarmer
{
    private const RANKINGS_PREVIEW_LIMIT_DEFAULT = 5;

    public function __construct(
        private readonly CueScoreRankingsPreviewBuilder $rankingsPreviewBuilder,
    ) {
    }

    public function rankingsPreview(string $discipline): void
    {
        $limit = self::RANKINGS_PREVIEW_LIMIT_DEFAULT;

        Cache::remember(
            CacheKeys::rankingsPreview($discipline, $limit),
            now()->addMinutes(5),
            fn () => $this->rankingsPreviewBuilder->build($discipline, $limit)
        );
    }

    public function disciplinePage(string $discipline): void
    {
        $this->rankingsPreview($discipline);
    }
}