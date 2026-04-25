<?php

namespace App\Support;

use App\Services\CueScore\CueScoreRankingsPreviewBuilder;
use Illuminate\Support\Facades\Cache;

/**
 * Service responsable du préchauffage du cache API public.
 *
 * Ce service est complémentaire à ApiCacheInvalidator :
 * - ApiCacheInvalidator → supprime le cache
 * - ApiCacheWarmer → reconstruit immédiatement le cache
 *
 * Objectif :
 * éviter un cache cold (premier appel lent côté utilisateur)
 * après une invalidation liée à une mise à jour des données.
 */
class ApiCacheWarmer
{
    /**
     * Limite par défaut utilisée pour le preview des classements.
     *
     * Seule cette limite est préchauffée pour éviter un coût inutile.
     */
    private const RANKINGS_PREVIEW_LIMIT_DEFAULT = 5;

    public function __construct(
        private readonly CueScoreRankingsPreviewBuilder $rankingsPreviewBuilder,
    ) {
    }

    /**
     * Préchauffe le cache du preview des classements pour une discipline.
     *
     * Seule la limite par défaut est préchauffée, les autres variantes
     * seront générées à la demande si nécessaire.
     *
     * @param string $discipline
     *
     * @return void
     */
    public function rankingsPreview(string $discipline): void
    {
        $limit = self::RANKINGS_PREVIEW_LIMIT_DEFAULT;

        Cache::remember(
            CacheKeys::rankingsPreview($discipline, $limit),
            now()->addMinutes(5),
            fn () => $this->rankingsPreviewBuilder->build($discipline, $limit)
        );
    }

    /**
     * Préchauffe les caches liés à une page discipline.
     *
     * Actuellement :
     * - preview des classements (limite par défaut)
     *
     * Peut être étendu plus tard pour :
     * - page discipline complète
     * - autres endpoints dépendants
     *
     * @param string $discipline
     *
     * @return void
     */
    public function disciplinePage(string $discipline): void
    {
        $this->rankingsPreview($discipline);
    }
}