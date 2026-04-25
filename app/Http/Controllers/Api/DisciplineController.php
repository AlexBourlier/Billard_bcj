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
use App\Services\CueScoreClubRankingService;
use App\Services\CueScore\CueScoreRankingsPreviewBuilder;
use App\Support\DisciplineMapper;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use App\Support\CacheKeys;
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
        private CueScoreClubRankingService $clubRankingService,
        private CueScoreRankingsPreviewBuilder $rankingsPreviewBuilder,
    ) {
    }

    /**
     * Retourne les données publiques d’une discipline.
     *
     * La réponse est mise en cache pendant 10 minutes.
     *
     * Contenu retourné :
     * - posts
     * - événements calendrier
     * - documents
     * - classements actifs, sauf pour la carambole
     *
     * @param string $discipline Slug de la discipline
     *
     * @return JsonResponse
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
     * Retourne un aperçu des classements CueScore pour une discipline.
     *
     * La réponse est mise en cache pendant 5 minutes.
     *
     * Le paramètre query `limit` contrôle le nombre d’entrées individuelles
     * retournées par classement. Il est borné entre 1 et 10.
     *
     * La construction de la réponse est déléguée à CueScoreRankingsPreviewBuilder.
     *
     * @param string $discipline Slug de la discipline
     *
     * @return JsonResponse
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