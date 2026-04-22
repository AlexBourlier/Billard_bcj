<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ClubMatchingRule;
use App\Models\CueScorePlayerMapping;
use App\Models\CueScoreRanking;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class CueScoreController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $rankings = $this->applyRankingFilters(
            CueScoreRanking::query()->orderBy('sort_order')->orderBy('id'),
            $request,
            false
        )->get();

        $data = $rankings->map(fn (CueScoreRanking $ranking) => $this->serializeRanking($ranking));

        return response()->json([
            'count' => $data->count(),
            'data' => $data,
        ]);
    }

    public function show(CueScoreRanking $ranking): JsonResponse
    {
        $activeFetch = $this->getActiveFetch($ranking);

        return response()->json([
            'data' => array_merge(
                $this->serializeRanking($ranking),
                [
                    'active_fetch' => $this->serializeFetch($activeFetch),
                ]
            ),
        ]);
    }

    public function club(CueScoreRanking $ranking): JsonResponse
    {
        $activeFetch = $this->getActiveFetch($ranking);

        if ($activeFetch === null) {
            return $this->emptyRankingResponse($ranking, 'Aucun fetch actif trouvé pour ce classement.');
        }

        $data = $ranking->ranking_type === 'team'
            ? $this->buildTeamRankingData($ranking, $activeFetch->id)
            : $this->buildIndividualRankingData($ranking, $activeFetch->id);

        return response()->json([
            'count' => $data['data']->count(),
            'data' => $data['data']->values(),
            'meta' => array_merge(
                $this->buildRankingMeta($ranking, $activeFetch->id),
                $data['meta']
            ),
        ]);
    }

    public function teams(CueScoreRanking $ranking): JsonResponse
    {
        $activeFetch = $this->getActiveFetch($ranking);

        if ($activeFetch === null) {
            return $this->emptyRankingResponse($ranking, 'Aucun fetch actif trouvé pour ce classement.');
        }

        $data = $this->buildTeamRankingData($ranking, $activeFetch->id);

        return response()->json([
            'count' => $data['data']->count(),
            'data' => $data['data']->values(),
            'meta' => array_merge(
                $this->buildRankingMeta($ranking, $activeFetch->id),
                $data['meta']
            ),
        ]);
    }

    public function clubOverview(Request $request): JsonResponse
    {
        return $this->clubOverviewFromFilters([
            'discipline' => $request->string('discipline')->toString(),
            'scope' => $request->string('scope')->toString(),
            'ranking_type' => $request->string('ranking_type')->toString(),
        ]);
    }

    public function byDisciplineScopeAndType(string $discipline, string $scope, string $rankingType): JsonResponse
    {
        return $this->clubOverviewFromFilters([
            'discipline' => $discipline,
            'scope' => $scope,
            'ranking_type' => $rankingType,
        ]);
    }

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

    private function getActiveFetch(CueScoreRanking $ranking)
    {
        return $ranking->fetches()
            ->where('is_active', true)
            ->latest('id')
            ->first();
    }

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

    private function buildIndividualRankingData(CueScoreRanking $ranking, int $fetchId): array
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

    private function buildTeamRankingData(CueScoreRanking $ranking, int $fetchId): array
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

    private function emptyRankingResponse(CueScoreRanking $ranking, string $message): JsonResponse
    {
        return response()->json([
            'count' => 0,
            'data' => [],
            'meta' => [
                'ranking_id' => $ranking->id,
                'ranking_name' => $ranking->name,
                'message' => $message,
            ],
        ]);
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
            $activeFetch = $this->getActiveFetch($ranking);

            if ($activeFetch === null) {
                return [
                    'ranking' => $this->serializeRanking($ranking),
                    'fetch' => null,
                    'count' => 0,
                    'data' => [],
                ];
            }

            $rankingData = $ranking->ranking_type === 'team'
                ? $this->buildTeamRankingData($ranking, $activeFetch->id)
                : $this->buildIndividualRankingData($ranking, $activeFetch->id);

            return [
                'ranking' => $this->serializeRanking($ranking),
                'fetch' => $this->serializeFetch($activeFetch),
                'count' => $rankingData['data']->count(),
                'data' => $rankingData['data']->values(),
                ...$rankingData['meta'],
            ];
        })->values();

        return response()->json([
            'count' => $data->count(),
            'filters' => $filters,
            'data' => $data,
        ]);
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
}