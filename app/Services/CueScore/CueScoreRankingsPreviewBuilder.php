<?php

namespace App\Services\CueScore;

use App\Models\CueScoreRanking;
use App\Services\CueScoreClubRankingService;
use Illuminate\Support\Collection;

class CueScoreRankingsPreviewBuilder
{
    public function __construct(
        private readonly CueScoreClubRankingService $clubRankingService,
    ) {
    }

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
}