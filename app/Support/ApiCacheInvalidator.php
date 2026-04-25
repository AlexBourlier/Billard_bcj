<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;

/**
 * Service responsable de l'invalidation du cache API public.
 *
 * Ce service centralise toutes les clés de cache utilisées par l'API
 * afin de garantir une invalidation cohérente après une mise à jour
 * des données (ex: import CueScore, modification contenu).
 *
 * Il agit uniquement en suppression (Cache::forget) et ne reconstruit
 * pas les données (voir ApiCacheWarmer pour le préchauffage).
 */
class ApiCacheInvalidator
{
    /**
     * Liste des disciplines publiques supportées.
     */
    private const DISCIPLINES = [
        'blackball',
        'americain',
        'snooker',
        'carambole',
    ];

    /**
     * Bornes des limites utilisées pour le preview des classements.
     */
    private const RANKINGS_PREVIEW_LIMIT_MIN = 1;
    private const RANKINGS_PREVIEW_LIMIT_MAX = 10;

    /**
     * Invalide le cache de la page d’accueil publique.
     *
     * @return void
     */
    public function publicHome(): void
    {
        Cache::forget(CacheKeys::publicHome());
    }

    /**
     * Invalide le cache d’une page discipline.
     *
     * @param string $discipline
     *
     * @return void
     */
    public function discipline(string $discipline): void
    {
        Cache::forget(CacheKeys::discipline($discipline));
    }

    /**
     * Invalide toutes les variantes du cache de preview des classements.
     *
     * Chaque limite (1 à 10) correspond à une clé de cache distincte.
     *
     * @param string $discipline
     *
     * @return void
     */
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

    /**
     * Invalide tous les caches liés à une discipline.
     *
     * Inclut :
     * - page discipline
     * - preview des classements
     *
     * @param string $discipline
     *
     * @return void
     */
    public function disciplinePage(string $discipline): void
    {
        $this->discipline($discipline);
        $this->rankingsPreview($discipline);
    }

    /**
     * Invalide l’ensemble du cache public de l’API.
     *
     * Inclut :
     * - page d’accueil
     * - toutes les disciplines
     *
     * @return void
     */
    public function allPublic(): void
    {
        $this->publicHome();

        foreach (self::DISCIPLINES as $discipline) {
            $this->disciplinePage($discipline);
        }
    }
}