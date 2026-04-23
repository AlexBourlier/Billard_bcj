<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CalendarEventResource;
use App\Http\Resources\DocumentResource;
use App\Http\Resources\PostResource;
use App\Models\Calendar;
use App\Models\ClubMatchingRule;
use App\Models\CueScorePlayerMapping;
use App\Models\CueScoreRanking;
use App\Models\Document;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class DisciplineController extends Controller
{
    public function show(string $discipline): JsonResponse
    {
        if (!$this->isValidDiscipline($discipline)) {
            return response()->json([
                'message' => 'Discipline not found',
                'error' => 'discipline_not_found',
            ], 404);
        }

        $disciplineId = $this->getDisciplineId($discipline);

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

        return response()->json([
            'discipline' => $discipline,
            'data' => [
                'posts' => PostResource::collection($posts),
                'calendar' => CalendarEventResource::collection($calendarEvents),
                'documents' => DocumentResource::collection($documents),
                'rankings' => $rankings,
            ],
            'meta' => [
                'posts_count' => $posts->count(),
                'calendar_count' => $calendarEvents->count(),
                'documents_count' => $documents->count(),
                'rankings_count' => $rankings === null ? null : $rankings->count(),
            ],
            'error' => null,
        ]);
    }

    public function rankingsPreview(string $discipline): JsonResponse
    {
        if (!$this->isValidDiscipline($discipline)) {
            return response()->json([
                'message' => 'Discipline not found',
                'error' => 'discipline_not_found',
            ], 404);
        }

        if ($discipline === 'carambole') {
            return response()->json([
                'discipline' => $discipline,
                'data' => null,
                'error' => null,
            ]);
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
            ->map(function (CueScoreRanking $ranking) {
                $activeFetch = $this->getActiveFetch($ranking);

                if ($activeFetch === null) {
                    return null;
                }

                $rankingData = $ranking->ranking_type === 'team'
                    ? $this->buildPreviewTeamRankingData($ranking, $activeFetch->id)
                    : $this->buildPreviewIndividualRankingData($ranking, $activeFetch->id);

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

        return response()->json([
            'discipline' => $discipline,
            'data' => $grouped,
            'error' => null,
        ]);
    }

    private function buildPreviewIndividualRankingData(CueScoreRanking $ranking, int $fetchId): array
    {
        $entries = $ranking->entries()
            ->where('cuescore_ranking_fetch_id', $fetchId)
            ->where('entry_type', 'player')
            ->orderBy('rank_position')
            ->get();

        $participantIds = $entries
            ->pluck('participant_external_id')
            ->filter()
            ->map(fn ($id) => (string) $id)
            ->unique()
            ->values();

        $mappings = CueScorePlayerMapping::query()
            ->with('licencie')
            ->whereIn('cuescore_participant_id', $participantIds)
            ->where('is_confirmed', true)
            ->get()
            ->keyBy(fn ($mapping) => (string) $mapping->cuescore_participant_id);

        $data = $entries
            ->filter(fn ($entry) => $entry->participant_external_id !== null
                && $mappings->has((string) $entry->participant_external_id))
            ->take($this->getPreviewLimit())
            ->map(function ($entry) use ($mappings) {
                $mapping = $mappings->get((string) $entry->participant_external_id);
                $licencie = $mapping?->licencie;

                return [
                    'rank_position' => $entry->rank_position,
                    'participant_name' => $entry->participant_name,
                    'participant_external_id' => $entry->participant_external_id,
                    'participant_url' => $entry->participant_url,
                    'points' => $entry->points,
                    'played' => $entry->played,
                    'wins' => $entry->wins,
                    'losses' => $entry->losses,
                    'ties' => $entry->ties,
                    'matching' => [
                        'method' => $mapping?->matching_method,
                        'confidence_score' => $mapping?->confidence_score,
                        'is_confirmed' => (bool) $mapping?->is_confirmed,
                    ],
                    'licencie' => $licencie ? [
                        'id' => $licencie->id,
                        'licence' => $licencie->licence ?? null,
                        'nom' => $licencie->nom ?? null,
                        'prenom' => $licencie->prenom ?? null,
                    ] : null,
                ];
            })
            ->values();

        return [
            'data' => $data,
            'meta' => [],
        ];
    }

    private function buildPreviewTeamRankingData(CueScoreRanking $ranking, int $fetchId): array
    {
        $entries = $ranking->entries()
            ->where('cuescore_ranking_fetch_id', $fetchId)
            ->where('entry_type', 'team')
            ->orderBy('rank_position')
            ->get();

        $rules = ClubMatchingRule::query()
            ->where('is_active', true)
            ->get();

        $hasClubTeam = $entries->contains(
            fn ($entry) => $this->teamMatchesClubRules($entry->team_name, $rules)
        );

        if (! $hasClubTeam) {
            return [
                'data' => collect(),
                'meta' => [
                    'club_team_present' => false,
                ],
            ];
        }

        $data = $entries->map(function ($entry) use ($rules) {
            return [
                'rank_position' => $entry->rank_position,
                'team_name' => $entry->team_name,
                'team_external_id' => $entry->team_external_id,
                'team_url' => $entry->team_url,
                'points' => $entry->points,
                'played' => $entry->played,
                'wins' => $entry->wins,
                'losses' => $entry->losses,
                'ties' => $entry->ties,
                'is_club_team' => $this->teamMatchesClubRules($entry->team_name, $rules),
                'additional_data' => $entry->additional_data,
            ];
        })->values();

        return [
            'data' => $data,
            'meta' => [
                'club_team_present' => true,
            ],
        ];
    }

    private function getActiveFetch(CueScoreRanking $ranking)
    {
        return $ranking->fetches()
            ->where('is_active', true)
            ->latest('id')
            ->first();
    }

    private function teamMatchesClubRules(?string $teamName, Collection $rules): bool
    {
        $teamName = $this->normalizeClubText($teamName);

        foreach ($rules as $rule) {
            $value = $this->normalizeClubText((string) $rule->matching_value);

            if ($value === '') {
                continue;
            }

            if ($rule->matching_mode === 'contains' && str_contains($teamName, $value)) {
                return true;
            }

            if ($rule->matching_mode === 'equals' && $teamName === $value) {
                return true;
            }

            if ($rule->matching_mode === 'starts_with' && str_starts_with($teamName, $value)) {
                return true;
            }
        }

        return false;
    }

    private function normalizeClubText(?string $value): string
    {
        if ($value === null) {
            return '';
        }

        $value = trim($value);
        $value = mb_strtolower($value, 'UTF-8');

        $replacements = [
            'à' => 'a', 'á' => 'a', 'â' => 'a', 'ä' => 'a',
            'ç' => 'c',
            'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e',
            'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
            'ñ' => 'n',
            'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'ö' => 'o',
            'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u',
            'ý' => 'y', 'ÿ' => 'y',
            '\'' => ' ',
            '-' => ' ',
        ];

        $value = strtr($value, $replacements);
        $value = preg_replace('/\s+/', ' ', $value) ?? $value;

        return trim($value);
    }

    private function isValidDiscipline(string $discipline): bool
    {
        return in_array($discipline, [
            'blackball',
            'carambole',
            'snooker',
            'americain',
        ], true);
    }

    private function getPreviewLimit(): int
    {
        $limit = (int) request('limit', 5);

        if ($limit < 1) {
            return 5;
        }

        return min($limit, 10);
    }

    private function getDisciplineId(string $discipline): ?int
    {
        $mapping = [
            'blackball' => 1,
            'carambole' => 2,
            'snooker' => 3,
            'americain' => 4,
        ];

        return $mapping[$discipline] ?? null;
    }
}