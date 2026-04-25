<?php

namespace App\Services\CueScore;

use App\Models\CueScoreRanking;
use App\Services\CueScoreClubRankingService;
use Illuminate\Support\Collection;

/**
 * Service responsable de la construction du preview des classements CueScore.
 *
 * Ce service est utilisé par l'API publique pour produire une version
 * légère et optimisée des classements par discipline.
 *
 * Responsabilités :
 * - filtrer les classements actifs par discipline
 * - récupérer les données via CueScoreClubRankingService
 * - appliquer une limite sur les entrées individuelles
 * - regrouper les résultats par scope
 * - formater la réponse finale prête pour l'API
 */
class CueScoreRankingsPreviewBuilder
{
    public function __construct(
        private readonly CueScoreClubRankingService $clubRankingService,
    ) {
    }

    /**
     * Construit le preview des classements pour une discipline donnée.
     *
     * Règles :
     * - la discipline "carambole" ne supporte pas les classements → réponse spécifique
     * - seuls les classements actifs sont pris en compte
     * - les classements sont groupés par scope
     * - les classements sans fetch actif ou sans données sont ignorés
     * - la limite ne s'applique qu'aux classements individuels
     *
     * @param string $discipline Slug de la discipline
     * @param int $limit Nombre maximum d'entrées pour les classements individuels
     *
     * @return array{
     *     data: \Illuminate\Support\Collection|null,
     *     meta: array{
     *         discipline: string,
     *         count: int,
     *         limit: int,
     *         rankings_supported: bool
     *     },
     *     links: array,
     *     error: null
     * }
     */
    public function build(string $discipline, int $limit): array
    {
        if ($discipline === 'carambole') {
            return [
                'data' => null,
                'meta' => [
                    'discipline' => $discipline,
                    'rankings_supported' => false,
                ],
                'links' => [],
                'error' => null,
            ];
        }

        $rankings = CueScoreRanking::query()
            ->where('discipline', $discipline)
            ->where('is_active', true)
            ->orderBy('scope')
            ->orderBy('ranking_type')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $grouped = $rankings
            ->map(function (CueScoreRanking $ranking) use ($limit) {
                $activeFetch = $this->clubRankingService->getActiveFetch($ranking);

                // Ignorer les classements sans données exploitables
                if ($activeFetch === null) {
                    return null;
                }

                $rankingData = $ranking->ranking_type === 'team'
                    ? $this->clubRankingService->buildTeamRankingData($ranking, $activeFetch->id)
                    : $this->clubRankingService->buildIndividualRankingData(
                        $ranking,
                        $activeFetch->id,
                        $limit
                    );

                // Ignorer les classements vides
                if ($rankingData['data']->isEmpty()) {
                    return null;
                }

                return [
                    'scope' => $ranking->scope,
                    'ranking' => [
                        'id' => $ranking->id,
                        'name' => $ranking->name,
                        'cuescore_id' => $ranking->cuescore_id,
                        'url' => $ranking->url,
                        'source_type' => $ranking->source_type,
                        'discipline' => $ranking->discipline,
                        'scope' => $ranking->scope,
                        'ranking_type' => $ranking->ranking_type,
                        'team_category' => $ranking->team_category,
                        'season' => $ranking->season,
                        'is_active' => (bool) $ranking->is_active,
                        'sort_order' => $ranking->sort_order,
                    ],
                    'entries' => $rankingData['data']->values(),
                    'meta' => $rankingData['meta'],
                ];
            })
            ->filter()
            ->groupBy('scope')
            ->map(function (Collection $items) {
                return $items->map(function (array $item) {
                    unset($item['scope']);

                    return $item;
                })->values();
            });

        return [
            'data' => $grouped,
            'meta' => [
                'discipline' => $discipline,
                'count' => $grouped->flatten(1)->count(),
                'limit' => $limit,
                'rankings_supported' => true,
            ],
            'links' => [],
            'error' => null,
        ];
    }
}