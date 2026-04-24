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
use App\Support\DisciplineMapper;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use App\Support\CacheKeys;
use Illuminate\Support\Facades\Cache;

class DisciplineController extends Controller
{
    public function __construct(
        private CueScoreClubRankingService $clubRankingService
    ) {
    }

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

    public function rankingsPreview(string $discipline): JsonResponse
    {
        if (!DisciplineMapper::isValidSlug($discipline)) {
            return $this->disciplineNotFoundResponse();
        }

        $limit = $this->getPreviewLimit();

        $response = Cache::remember(
            CacheKeys::rankingsPreview($discipline, $limit),
            now()->addMinutes(5),
            function () use ($discipline, $limit) {
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
        );

        return response()->json($response);
    }

    private function getPreviewLimit(): int
    {
        $limit = (int) request('limit', 5);

        if ($limit < 1) {
            return 5;
        }

        return min($limit, 10);
    }

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