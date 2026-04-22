<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CueScoreRanking;
use App\Models\CueScorePlayerMapping;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CueScoreController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = CueScoreRanking::query()->orderBy('sort_order')->orderBy('id');

        if ($request->filled('discipline')) {
            $query->where('discipline', $request->string('discipline')->toString());
        }

        if ($request->filled('scope')) {
            $query->where('scope', $request->string('scope')->toString());
        }

        if ($request->filled('ranking_type')) {
            $query->where('ranking_type', $request->string('ranking_type')->toString());
        }

        if ($request->filled('team_category')) {
            $query->where('team_category', $request->string('team_category')->toString());
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', filter_var($request->input('is_active'), FILTER_VALIDATE_BOOL));
        }

        $rankings = $query->get()->map(function (CueScoreRanking $ranking): array {
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
        });

        return response()->json([
            'count' => $rankings->count(),
            'data' => $rankings,
        ]);
    }

    public function show(CueScoreRanking $ranking): JsonResponse
    {
        $activeFetch = $ranking->fetches()
            ->where('is_active', true)
            ->latest('id')
            ->first();

        return response()->json([
            'data' => [
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
                'active_fetch' => $activeFetch ? [
                    'id' => $activeFetch->id,
                    'status' => $activeFetch->status,
                    'fetched_at' => $activeFetch->fetched_at?->toIso8601String(),
                    'http_status' => $activeFetch->http_status,
                    'records_count' => $activeFetch->records_count,
                    'is_active' => (bool) $activeFetch->is_active,
                ] : null,
            ],
        ]);
    }

    public function club(CueScoreRanking $ranking): JsonResponse
    {
        $activeFetch = $ranking->fetches()
            ->where('is_active', true)
            ->latest('id')
            ->first();

        if ($activeFetch === null) {
            return response()->json([
                'data' => [],
                'meta' => [
                    'ranking_id' => $ranking->id,
                    'ranking_name' => $ranking->name,
                    'message' => 'Aucun fetch actif trouvé pour ce classement.',
                ],
            ]);
        }

        $entries = $ranking->entries()
            ->where('cuescore_ranking_fetch_id', $activeFetch->id)
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
            ->filter(function ($entry) use ($mappings) {
                return $entry->participant_external_id !== null
                    && $mappings->has((string) $entry->participant_external_id);
            })
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

        return response()->json([
            'count' => $data->count(),
            'data' => $data,
            'meta' => [
                'ranking_id' => $ranking->id,
                'ranking_name' => $ranking->name,
                'fetch_id' => $activeFetch->id,
                'discipline' => $ranking->discipline,
                'scope' => $ranking->scope,
                'ranking_type' => $ranking->ranking_type,
            ],
        ]);
    }

    public function teams(CueScoreRanking $ranking): JsonResponse
    {
        $activeFetch = $ranking->fetches()
            ->where('is_active', true)
            ->latest('id')
            ->first();

        if ($activeFetch === null) {
            return response()->json([
                'data' => [],
                'meta' => [
                    'ranking_id' => $ranking->id,
                    'ranking_name' => $ranking->name,
                    'message' => 'Aucun fetch actif trouvé pour ce classement.',
                ],
            ]);
        }

        $entries = $ranking->entries()
            ->where('cuescore_ranking_fetch_id', $activeFetch->id)
            ->where('entry_type', 'team')
            ->orderBy('rank_position')
            ->get();

        $rules = \App\Models\ClubMatchingRule::query()
            ->where('is_active', true)
            ->get();

        $hasClubTeam = $entries->contains(function ($entry) use ($rules) {
            return $this->teamMatchesClubRules($entry->team_name, $rules);
        });

        if (! $hasClubTeam) {
            return response()->json([
                'count' => 0,
                'data' => [],
                'meta' => [
                    'ranking_id' => $ranking->id,
                    'ranking_name' => $ranking->name,
                    'fetch_id' => $activeFetch->id,
                    'discipline' => $ranking->discipline,
                    'scope' => $ranking->scope,
                    'ranking_type' => $ranking->ranking_type,
                    'team_category' => $ranking->team_category,
                    'club_team_present' => false,
                ],
            ]);
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

        return response()->json([
            'count' => $data->count(),
            'data' => $data,
            'meta' => [
                'ranking_id' => $ranking->id,
                'ranking_name' => $ranking->name,
                'fetch_id' => $activeFetch->id,
                'discipline' => $ranking->discipline,
                'scope' => $ranking->scope,
                'ranking_type' => $ranking->ranking_type,
                'team_category' => $ranking->team_category,
                'club_team_present' => true,
            ],
        ]);
    }

    public function clubOverview(Request $request): JsonResponse
    {
        $query = CueScoreRanking::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id');

        if ($request->filled('discipline')) {
            $query->where('discipline', $request->string('discipline')->toString());
        }

        if ($request->filled('scope')) {
            $query->where('scope', $request->string('scope')->toString());
        }

        if ($request->filled('ranking_type')) {
            $query->where('ranking_type', $request->string('ranking_type')->toString());
        }

        if ($request->filled('team_category')) {
            $query->where('team_category', $request->string('team_category')->toString());
        }

        $rankings = $query->get();

        $rules = \App\Models\ClubMatchingRule::query()
            ->where('is_active', true)
            ->get();

        $data = $rankings->map(function (CueScoreRanking $ranking) use ($rules) {
            $activeFetch = $ranking->fetches()
                ->where('is_active', true)
                ->latest('id')
                ->first();

            if ($activeFetch === null) {
                return [
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
                    ],
                    'fetch' => null,
                    'count' => 0,
                    'data' => [],
                ];
            }

            if ($ranking->ranking_type === 'individual') {
                $entries = $ranking->entries()
                    ->where('cuescore_ranking_fetch_id', $activeFetch->id)
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

                $rankingData = $entries
                    ->filter(function ($entry) use ($mappings) {
                        return $entry->participant_external_id !== null
                            && $mappings->has((string) $entry->participant_external_id);
                    })
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
                    ],
                    'fetch' => [
                        'id' => $activeFetch->id,
                        'status' => $activeFetch->status,
                        'fetched_at' => $activeFetch->fetched_at?->toIso8601String(),
                        'records_count' => $activeFetch->records_count,
                    ],
                    'count' => $rankingData->count(),
                    'data' => $rankingData,
                ];
            }

            $entries = $ranking->entries()
                ->where('cuescore_ranking_fetch_id', $activeFetch->id)
                ->where('entry_type', 'team')
                ->orderBy('rank_position')
                ->get();

            $hasClubTeam = $entries->contains(function ($entry) use ($rules) {
                return $this->teamMatchesClubRules($entry->team_name, $rules);
            });

            $rankingData = collect();

            if ($hasClubTeam) {
                $rankingData = $entries->map(function ($entry) use ($rules) {
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
            }

            return [
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
                ],
                'fetch' => [
                    'id' => $activeFetch->id,
                    'status' => $activeFetch->status,
                    'fetched_at' => $activeFetch->fetched_at?->toIso8601String(),
                    'records_count' => $activeFetch->records_count,
                ],
                'count' => $rankingData->count(),
                'club_team_present' => $hasClubTeam,
                'data' => $rankingData,
            ];
        })->values();

        return response()->json([
            'count' => $data->count(),
            'filters' => [
                'discipline' => $request->query('discipline'),
                'scope' => $request->query('scope'),
                'ranking_type' => $request->query('ranking_type'),
                'team_category' => $request->query('team_category'),
            ],
            'data' => $data,
        ]);
    }

    private function teamMatchesClubRules(?string $teamName, $rules): bool
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
}