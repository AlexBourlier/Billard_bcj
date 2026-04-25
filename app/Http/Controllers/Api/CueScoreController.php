<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CueScoreRanking;
use App\Services\CueScoreClubRankingService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Contrôleur API pour l'exposition des classements CueScore.
 *
 * Permet de :
 * - lister les classements disponibles
 * - consulter un classement
 * - récupérer les données club (individuelles ou équipes)
 * - construire des vues agrégées filtrées
 *
 * Les réponses respectent le format standard :
 * data / meta / links / error
 */
class CueScoreController extends Controller
{
    public function __construct(
        private CueScoreClubRankingService $clubRankingService
    ) {
    }

    /**
     * Liste les classements CueScore avec filtres optionnels.
     *
     * Filtres disponibles :
     * - discipline
     * - scope
     * - ranking_type
     * - team_category
     * - is_active
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $rankings = $this->applyRankingFilters(
            CueScoreRanking::query()->orderBy('sort_order')->orderBy('id'),
            $request,
            false
        )->get();

        $data = $rankings->map(fn (CueScoreRanking $ranking) => $this->serializeRanking($ranking));

        return response()->json([
            'data' => $data,
            'meta' => [
                'count' => $data->count(),
            ],
            'links' => [],
            'error' => null,
        ]);
    }

    /**
     * Retourne les informations d’un classement avec son fetch actif.
     *
     * @param CueScoreRanking $ranking
     * @return JsonResponse
     */
    public function show(CueScoreRanking $ranking): JsonResponse
    {
        $activeFetch = $this->clubRankingService->getActiveFetch($ranking);

        return response()->json([
            'data' => array_merge(
                $this->serializeRanking($ranking),
                [
                    'active_fetch' => $this->serializeFetch($activeFetch),
                ]
            ),
            'meta' => [],
            'links' => [],
            'error' => null,
        ]);
    }

    /**
     * Retourne les données club d’un classement (individuel ou équipe).
     *
     * Si aucun fetch actif n’est disponible, retourne une réponse vide.
     *
     * @param CueScoreRanking $ranking
     * @return JsonResponse
     */
    public function club(CueScoreRanking $ranking): JsonResponse
    {
        $activeFetch = $this->clubRankingService->getActiveFetch($ranking);

        if ($activeFetch === null) {
            return $this->emptyRankingResponse($ranking, 'Aucun fetch actif trouvé pour ce classement.');
        }

        $rankingData = $ranking->ranking_type === 'team'
            ? $this->clubRankingService->buildTeamRankingData($ranking, $activeFetch->id)
            : $this->clubRankingService->buildIndividualRankingData($ranking, $activeFetch->id);

        return response()->json([
            'data' => $rankingData['data']->values(),
            'meta' => array_merge(
                [
                    'count' => $rankingData['data']->count(),
                ],
                $this->buildRankingMeta($ranking, $activeFetch->id),
                $rankingData['meta']
            ),
            'links' => [],
            'error' => null,
        ]);
    }

    /**
     * Retourne uniquement les classements équipes pour un ranking.
     *
     * @param CueScoreRanking $ranking
     * @return JsonResponse
     */
    public function teams(CueScoreRanking $ranking): JsonResponse
    {
        $activeFetch = $this->clubRankingService->getActiveFetch($ranking);

        if ($activeFetch === null) {
            return $this->emptyRankingResponse($ranking, 'Aucun fetch actif trouvé pour ce classement.');
        }

        $rankingData = $this->clubRankingService->buildTeamRankingData($ranking, $activeFetch->id);

        return response()->json([
            'data' => $rankingData['data']->values(),
            'meta' => array_merge(
                [
                    'count' => $rankingData['data']->count(),
                ],
                $this->buildRankingMeta($ranking, $activeFetch->id),
                $rankingData['meta']
            ),
            'links' => [],
            'error' => null,
        ]);
    }

    /**
     * Vue agrégée des classements club basée sur des filtres query.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function clubOverview(Request $request): JsonResponse
    {
        return $this->clubOverviewFromFilters([
            'discipline' => $request->string('discipline')->toString(),
            'scope' => $request->string('scope')->toString(),
            'ranking_type' => $request->string('ranking_type')->toString(),
        ]);
    }

    /**
     * Vue agrégée des classements club via paramètres d’URL.
     *
     * @param string $discipline
     * @param string $scope
     * @param string $rankingType
     * @return JsonResponse
     */
    public function byDisciplineScopeAndType(string $discipline, string $scope, string $rankingType): JsonResponse
    {
        return $this->clubOverviewFromFilters([
            'discipline' => $discipline,
            'scope' => $scope,
            'ranking_type' => $rankingType,
        ]);
    }

    /**
     * Applique les filtres sur une requête de classement.
     *
     * @param Builder $query
     * @param Request $request
     * @param bool $forceActive
     * @return Builder
     */
    private function applyRankingFilters(Builder $query, Request $request, bool $forceActive): Builder
    {
        if ($forceActive) {
            $query->where('is_active', true);
        } elseif ($request->filled('is_active')) {
            $query->where(
                'is_active',
                filter_var($request->input('is_active'), FILTER_VALIDATE_BOOL)
            );
        }

        if ($request->filled('discipline')) {
            $query->where('discipline', mb_strtolower($request->string('discipline')->toString()));
        }

        if ($request->filled('scope')) {
            $query->where('scope', mb_strtolower($request->string('scope')->toString()));
        }

        if ($request->filled('ranking_type')) {
            $query->where('ranking_type', mb_strtolower($request->string('ranking_type')->toString()));
        }

        if ($request->filled('team_category')) {
            $query->where('team_category', strtoupper($request->string('team_category')->toString()));
        }

        return $query;
    }

    /**
     * Sérialise un modèle CueScoreRanking.
     *
     * @param CueScoreRanking $ranking
     * @return array
     */
    private function serializeRanking(CueScoreRanking $ranking): array
    {
        return [
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
        ];
    }

    /**
     * Sérialise un fetch actif CueScore.
     *
     * @param mixed $fetch
     * @return array|null
     */
    private function serializeFetch($fetch): ?array
    {
        if ($fetch === null) {
            return null;
        }

        return [
            'id' => $fetch->id,
            'status' => $fetch->status,
            'fetched_at' => $fetch->fetched_at?->toIso8601String(),
            'http_status' => $fetch->http_status,
            'records_count' => $fetch->records_count,
            'is_active' => (bool) $fetch->is_active,
        ];
    }

    /**
     * Construit les métadonnées d’un classement.
     *
     * @param CueScoreRanking $ranking
     * @param int $fetchId
     * @return array
     */
    private function buildRankingMeta(CueScoreRanking $ranking, int $fetchId): array
    {
        return [
            'ranking_id' => $ranking->id,
            'ranking_name' => $ranking->name,
            'fetch_id' => $fetchId,
            'discipline' => $ranking->discipline,
            'scope' => $ranking->scope,
            'ranking_type' => $ranking->ranking_type,
            'team_category' => $ranking->team_category,
        ];
    }

    /**
     * Retourne une réponse vide pour un classement sans données.
     *
     * @param CueScoreRanking $ranking
     * @param string $message
     * @return JsonResponse
     */
    private function emptyRankingResponse(CueScoreRanking $ranking, string $message): JsonResponse
    {
        return response()->json([
            'data' => [],
            'meta' => [
                'count' => 0,
                'ranking_id' => $ranking->id,
                'ranking_name' => $ranking->name,
                'message' => $message,
            ],
            'links' => [],
            'error' => null,
        ]);
    }

    /**
     * Construit une vue agrégée des classements avec filtres.
     *
     * @param array $filters
     * @return JsonResponse
     */
    private function clubOverviewFromFilters(array $filters): JsonResponse
    {
        $query = CueScoreRanking::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id');

        if (!empty($filters['discipline'])) {
            $query->where('discipline', mb_strtolower($filters['discipline']));
        }

        if (!empty($filters['scope'])) {
            $query->where('scope', mb_strtolower($filters['scope']));
        }

        if (!empty($filters['ranking_type'])) {
            $query->where('ranking_type', mb_strtolower($filters['ranking_type']));
        }

        $rankings = $query->get();

        $data = $rankings->map(function (CueScoreRanking $ranking) {
            $activeFetch = $this->clubRankingService->getActiveFetch($ranking);

            if ($activeFetch === null) {
                return [
                    'ranking' => $this->serializeRanking($ranking),
                    'fetch' => null,
                    'count' => 0,
                    'data' => [],
                ];
            }

            $rankingData = $ranking->ranking_type === 'team'
                ? $this->clubRankingService->buildTeamRankingData($ranking, $activeFetch->id)
                : $this->clubRankingService->buildIndividualRankingData($ranking, $activeFetch->id);

            return [
                'ranking' => $this->serializeRanking($ranking),
                'fetch' => $this->serializeFetch($activeFetch),
                'count' => $rankingData['data']->count(),
                'data' => $rankingData['data']->values(),
                ...$rankingData['meta'],
            ];
        })->values();

        return response()->json([
            'data' => $data,
            'meta' => [
                'count' => $data->count(),
                'filters' => $filters,
            ],
            'links' => [],
            'error' => null,
        ]);
    }
}