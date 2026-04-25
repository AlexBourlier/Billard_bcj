<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CalendarEventResource;
use App\Http\Resources\DocumentResource;
use App\Http\Resources\PostResource;
use App\Models\Calendar;
use App\Models\CueScoreRanking;
use App\Models\Document;
use App\Models\Post;
use App\Services\CueScore\CueScoreRankingsPreviewBuilder;
use App\Support\CacheKeys;
use App\Support\DisciplineMapper;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

/**
 * Contrôleur API dédié aux pages disciplines.
 *
 * Expose les données nécessaires au front public pour une discipline :
 * - articles
 * - documents
 * - événements calendrier
 * - classements CueScore
 *
 * Les réponses respectent le format standard :
 * data / meta / links / error
 */
class DisciplineController extends Controller
{
    private const PREVIEW_LIMIT_MIN = 1;
    private const PREVIEW_LIMIT_MAX = 10;
    private const PREVIEW_LIMIT_DEFAULT = 5;

    public function __construct(
        private CueScoreRankingsPreviewBuilder $rankingsPreviewBuilder,
    ) {
    }

    /**
     * Détail d'une discipline
     *
     * Retourne les données publiques nécessaires à la page d’une discipline :
     * articles, événements calendrier, documents et classements actifs.
     *
     * @group Disciplines
     *
     * La réponse est mise en cache pendant 10 minutes.
     *
     * @urlParam discipline string required Slug de la discipline. Exemple : blackball
     *
     * @response 200 {
     *   "data": {
     *     "posts": [],
     *     "calendar": [],
     *     "documents": [],
     *     "rankings": []
     *   },
     *   "meta": {
     *     "discipline": "blackball",
     *     "posts_count": 0,
     *     "calendar_count": 0,
     *     "documents_count": 0,
     *     "rankings_count": 0
     *   },
     *   "links": [],
     *   "error": null
     * }
     *
     * @response 404 {
     *   "data": null,
     *   "meta": [],
     *   "links": [],
     *   "error": {
     *     "code": "discipline_not_found",
     *     "message": "Discipline not found"
     *   }
     * }
     */
    public function show(string $discipline): JsonResponse
    {
        if (!DisciplineMapper::isValidSlug($discipline)) {
            return $this->disciplineNotFoundResponse();
        }

        $response = Cache::remember(
            CacheKeys::discipline($discipline),
            now()->addMinutes(10),
            function () use ($discipline) {
                $disciplineId = DisciplineMapper::idFromSlug($discipline);

                $posts = Post::query()
                    ->where('discipline', $disciplineId)
                    ->orderByDesc('created_at')
                    ->get();

                $documents = Document::query()
                    ->where('discipline', $disciplineId)
                    ->get();

                $calendarEvents = Calendar::query()
                    ->active()
                    ->byDiscipline($discipline)
                    ->with(['events.links'])
                    ->get()
                    ->pluck('events')
                    ->flatten()
                    ->sortBy('date_debut')
                    ->values();

                $rankings = null;

                if ($discipline !== 'carambole') {
                    $rankings = CueScoreRanking::query()
                        ->where('discipline', $discipline)
                        ->where('is_active', true)
                        ->orderBy('scope')
                        ->orderBy('ranking_type')
                        ->get();
                }

                return [
                    'data' => [
                        'posts' => PostResource::collection($posts),
                        'calendar' => CalendarEventResource::collection($calendarEvents),
                        'documents' => DocumentResource::collection($documents),
                        'rankings' => $rankings,
                    ],
                    'meta' => [
                        'discipline' => $discipline,
                        'posts_count' => $posts->count(),
                        'calendar_count' => $calendarEvents->count(),
                        'documents_count' => $documents->count(),
                        'rankings_count' => $rankings === null ? null : $rankings->count(),
                    ],
                    'links' => [],
                    'error' => null,
                ];
            }
        );

        return response()->json($response);
    }

    /**
     * Aperçu des classements CueScore
     *
     * Retourne un aperçu des classements CueScore pour une discipline donnée.
     *
     * @group Disciplines
     *
     * La réponse est mise en cache pendant 5 minutes.
     *
     * Le paramètre `limit` contrôle le nombre d’entrées individuelles retournées
     * par classement. Il est borné entre 1 et 10.
     *
     * @urlParam discipline string required Slug de la discipline. Exemple : blackball
     *
     * @queryParam limit integer Nombre maximum d’entrées individuelles par classement. Min: 1. Max: 10. Default: 5. Exemple : 5
     *
     * @response 200 {
     *   "data": {
     *     "national": [
     *       {
     *         "ranking": {
     *           "id": 1,
     *           "name": "Classement national",
     *           "cuescore_id": "123456",
     *           "url": "https://cuescore.com/ranking/example",
     *           "source_type": "ranking",
     *           "discipline": "blackball",
     *           "scope": "national",
     *           "ranking_type": "individual",
     *           "team_category": null,
     *           "season": "2025-2026",
     *           "is_active": true,
     *           "sort_order": 1
     *         },
     *         "entries": [
     *           {
     *             "rank_position": 1,
     *             "participant_name": "John Doe",
     *             "participant_external_id": "123456",
     *             "participant_url": "https://cuescore.com/player/John+Doe/123456",
     *             "points": "1200.00",
     *             "played": null,
     *             "wins": null,
     *             "losses": null,
     *             "ties": null,
     *             "matching": {
     *               "method": "exact_normalized",
     *               "confidence_score": 100,
     *               "is_confirmed": true
     *             },
     *             "licencie": {
     *               "id": 1,
     *               "licence": "123456 A",
     *               "nom": "DOE",
     *               "prenom": "JOHN"
     *             }
     *           }
     *         ],
     *         "meta": []
     *       }
     *     ]
     *   },
     *   "meta": {
     *     "discipline": "blackball",
     *     "count": 1,
     *     "limit": 5,
     *     "rankings_supported": true
     *   },
     *   "links": [],
     *   "error": null
     * }
     *
     * @response 404 {
     *   "data": null,
     *   "meta": [],
     *   "links": [],
     *   "error": {
     *     "code": "discipline_not_found",
     *     "message": "Discipline not found"
     *   }
     * }
     */
    public function rankingsPreview(string $discipline): JsonResponse
    {
        if (!DisciplineMapper::isValidSlug($discipline)) {
            return $this->disciplineNotFoundResponse();
        }

        $limit = $this->getPreviewLimit();

        $response = Cache::remember(
            CacheKeys::rankingsPreview($discipline, $limit),
            now()->addMinutes(5),
            fn () => $this->rankingsPreviewBuilder->build($discipline, $limit)
        );

        return response()->json($response);
    }

    /**
     * Résout et sécurise la limite d’entrées retournées par classement.
     *
     * Règles :
     * - valeur par défaut : 5
     * - minimum : 1
     * - maximum : 10
     *
     * @return int
     */
    private function getPreviewLimit(): int
    {
        return max(
            self::PREVIEW_LIMIT_MIN,
            min((int) request('limit', self::PREVIEW_LIMIT_DEFAULT), self::PREVIEW_LIMIT_MAX)
        );
    }

    /**
     * Retourne une réponse JSON standardisée lorsqu’une discipline est invalide.
     *
     * @return JsonResponse
     */
    private function disciplineNotFoundResponse(): JsonResponse
    {
        return response()->json([
            'data' => null,
            'meta' => [],
            'links' => [],
            'error' => [
                'code' => 'discipline_not_found',
                'message' => 'Discipline not found',
            ],
        ], 404);
    }
}