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
     * Liste des classements CueScore
     *
     * Retourne les classements CueScore disponibles avec filtres optionnels.
     *
     * @group CueScore
     *
     * @queryParam discipline string Filtrer par discipline. Exemple : blackball
     * @queryParam scope string Filtrer par scope. Exemple : national
     * @queryParam ranking_type string Filtrer par type de classement. Exemple : individual
     * @queryParam team_category string Filtrer par catégorie équipe. Exemple : DN1
     * @queryParam is_active boolean Filtrer les classements actifs/inactifs. Exemple : true
     *
     * @response 200 {
     *   "data": [
     *     {
     *       "id": 1,
     *       "name": "FFB - Blackball - TN - Master",
     *       "cuescore_id": "123456",
     *       "url": "https://cuescore.com/ranking/example",
     *       "source_type": "ranking",
     *       "discipline": "blackball",
     *       "scope": "national",
     *       "ranking_type": "individual",
     *       "team_category": null,
     *       "season": "2025-2026",
     *       "is_active": true,
     *       "sort_order": 1
     *     }
     *   ],
     *   "meta": {
     *     "count": 1
     *   },
     *   "links": [],
     *   "error": null
     * }
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
     * Détail d’un classement CueScore
     *
     * Retourne les informations d’un classement CueScore avec son fetch actif.
     *
     * @group CueScore
     *
     * @urlParam ranking integer required Identifiant du classement CueScore local. Exemple : 1
     *
     * @response 200 {
     *   "data": {
     *     "id": 1,
     *     "name": "FFB - Blackball - TN - Master",
     *     "cuescore_id": "123456",
     *     "url": "https://cuescore.com/ranking/example",
     *     "source_type": "ranking",
     *     "discipline": "blackball",
     *     "scope": "national",
     *     "ranking_type": "individual",
     *     "team_category": null,
     *     "season": "2025-2026",
     *     "is_active": true,
     *     "sort_order": 1,
     *     "active_fetch": {
     *       "id": 10,
     *       "status": "success",
     *       "fetched_at": "2026-04-25T10:00:00+00:00",
     *       "http_status": 200,
     *       "records_count": 120,
     *       "is_active": true
     *     }
     *   },
     *   "meta": [],
     *   "links": [],
     *   "error": null
     * }
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
     * Données club d’un classement
     *
     * Retourne les données du club pour un classement CueScore.
     *
     * Pour un classement individuel, seules les entrées correspondant à des licenciés confirmés sont retournées.
     * Pour un classement équipe, les équipes sont retournées si au moins une équipe du club est détectée.
     *
     * @group CueScore
     *
     * @urlParam ranking integer required Identifiant du classement CueScore local. Exemple : 1
     *
     * @response 200 {
     *   "data": [
     *     {
     *       "rank_position": 1,
     *       "participant_name": "John Doe",
     *       "participant_external_id": "123456",
     *       "participant_url": "https://cuescore.com/player/John+Doe/123456",
     *       "points": "1200.00",
     *       "played": null,
     *       "wins": null,
     *       "losses": null,
     *       "ties": null,
     *       "matching": {
     *         "method": "exact_normalized",
     *         "confidence_score": 100,
     *         "is_confirmed": true
     *       },
     *       "licencie": {
     *         "id": 1,
     *         "licence": "123456 A",
     *         "nom": "DOE",
     *         "prenom": "JOHN"
     *       }
     *     }
     *   ],
     *   "meta": {
     *     "count": 1,
     *     "ranking_id": 1,
     *     "ranking_name": "FFB - Blackball - TN - Master",
     *     "fetch_id": 10,
     *     "discipline": "blackball",
     *     "scope": "national",
     *     "ranking_type": "individual",
     *     "team_category": null
     *   },
     *   "links": [],
     *   "error": null
     * }
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
     * Données équipes d’un classement
     *
     * Retourne les données équipes d’un classement CueScore.
     *
     * Les équipes appartenant au club sont identifiées via les règles de matching club.
     *
     * @group CueScore
     *
     * @urlParam ranking integer required Identifiant du classement CueScore local. Exemple : 1
     *
     * @response 200 {
     *   "data": [
     *     {
     *       "rank_position": 1,
     *       "team_name": "JOUÉ LÈS TOURS 1 DN1",
     *       "team_external_id": "123456",
     *       "team_url": "https://cuescore.com/team/example",
     *       "points": "76.00",
     *       "played": 16,
     *       "wins": 13,
     *       "losses": 1,
     *       "ties": 2,
     *       "is_club_team": true,
     *       "additional_data": {
     *         "frame_wins": 193,
     *         "frame_score": 66,
     *         "frame_losses": 127
     *       }
     *     }
     *   ],
     *   "meta": {
     *     "count": 1,
     *     "ranking_id": 1,
     *     "ranking_name": "FFB - Blackball - Equipes - DN1",
     *     "fetch_id": 10,
     *     "discipline": "blackball",
     *     "scope": "national",
     *     "ranking_type": "team",
     *     "team_category": "DN1",
     *     "club_team_present": true
     *   },
     *   "links": [],
     *   "error": null
     * }
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
     * Vue club des classements CueScore
     *
     * Retourne une vue agrégée des classements club selon des filtres optionnels.
     *
     * @group CueScore
     *
     * @queryParam discipline string Filtrer par discipline. Exemple : blackball
     * @queryParam scope string Filtrer par scope. Exemple : national
     * @queryParam ranking_type string Filtrer par type de classement. Exemple : individual
     *
     * @response 200 {
     *   "data": [
     *     {
     *       "ranking": {
     *         "id": 1,
     *         "name": "FFB - Blackball - TN - Master",
     *         "cuescore_id": "123456",
     *         "url": "https://cuescore.com/ranking/example",
     *         "source_type": "ranking",
     *         "discipline": "blackball",
     *         "scope": "national",
     *         "ranking_type": "individual",
     *         "team_category": null,
     *         "season": "2025-2026",
     *         "is_active": true,
     *         "sort_order": 1
     *       },
     *       "fetch": {
     *         "id": 10,
     *         "status": "success",
     *         "fetched_at": "2026-04-25T10:00:00+00:00",
     *         "http_status": 200,
     *         "records_count": 120,
     *         "is_active": true
     *       },
     *       "count": 1,
     *       "data": []
     *     }
     *   ],
     *   "meta": {
     *     "count": 1,
     *     "filters": {
     *       "discipline": "blackball",
     *       "scope": "national",
     *       "ranking_type": "individual"
     *     }
     *   },
     *   "links": [],
     *   "error": null
     * }
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
     * Vue club par discipline, scope et type
     *
     * Retourne une vue agrégée des classements club à partir des paramètres d’URL.
     *
     * @group CueScore
     *
     * @urlParam discipline string required Slug de la discipline. Exemple : blackball
     * @urlParam scope string required Scope du classement. Exemple : national
     * @urlParam rankingType string required Type du classement. Exemple : individual
     *
     * @response 200 {
     *   "data": [
     *     {
     *       "ranking": {
     *         "id": 1,
     *         "name": "FFB - Blackball - TN - Master",
     *         "discipline": "blackball",
     *         "scope": "national",
     *         "ranking_type": "individual",
     *         "is_active": true
     *       },
     *       "fetch": {
     *         "id": 10,
     *         "status": "success",
     *         "fetched_at": "2026-04-25T10:00:00+00:00",
     *         "http_status": 200,
     *         "records_count": 120,
     *         "is_active": true
     *       },
     *       "count": 1,
     *       "data": []
     *     }
     *   ],
     *   "meta": {
     *     "count": 1,
     *     "filters": {
     *       "discipline": "blackball",
     *       "scope": "national",
     *       "ranking_type": "individual"
     *     }
     *   },
     *   "links": [],
     *   "error": null
     * }
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