<?php

namespace App\Services;

use App\Models\ClubMatchingRule;
use App\Models\CueScorePlayerMapping;
use App\Models\CueScoreRanking;
use Illuminate\Support\Collection;

class CueScoreClubRankingService
{
    public function getActiveFetch(CueScoreRanking $ranking)
    {
        return $ranking->fetches()
            ->where('is_active', true)
            ->latest('id')
            ->first();
    }

    public function buildIndividualRankingData(CueScoreRanking $ranking, int $fetchId, ?int $limit = null): array
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
                && $mappings->has((string) $entry->participant_external_id));

        if ($limit !== null) {
            $data = $data->take($limit);
        }

        $data = $data
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

    public function buildTeamRankingData(CueScoreRanking $ranking, int $fetchId): array
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

    public function teamMatchesClubRules(?string $teamName, Collection $rules): bool
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

    public function normalizeClubText(?string $value): string
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