<?php

namespace App\Services\CueScore;

class CueScoreEntryParser
{
    public function parseRankingParticipants(array $payload): array
    {
        $participants = $payload['participants'] ?? [];

        return collect($participants)->map(function (array $participant): array {
            return [
                'entry_type' => 'player',
                'rank_position' => $participant['rank'] ?? null,
                'participant_name' => $participant['name'] ?? null,
                'participant_external_id' => isset($participant['participantId']) ? (string) $participant['participantId'] : null,
                'participant_url' => $participant['url'] ?? null,
                'team_name' => null,
                'team_external_id' => null,
                'team_url' => null,
                'points' => $participant['points'] ?? null,
                'played' => null,
                'wins' => null,
                'losses' => null,
                'ties' => null,
                'additional_data' => [
                    'tournament_count' => $participant['tournamentCount'] ?? null,
                ],
            ];
        })->values()->all();
    }

    public function parseTournamentStandings(array $payload): array
    {
        $groups = $payload['standings'] ?? [];

        return collect($groups)
            ->flatten(1)
            ->map(function (array $entry): array {
                $team = $entry['player'] ?? [];

                return [
                    'entry_type' => 'team',
                    'rank_position' => $entry['position'] ?? null,
                    'participant_name' => null,
                    'participant_external_id' => null,
                    'participant_url' => null,
                    'team_name' => $team['name'] ?? null,
                    'team_external_id' => isset($team['teamId']) ? (string) $team['teamId'] : null,
                    'team_url' => $team['url'] ?? null,
                    'points' => $entry['points'] ?? null,
                    'played' => $entry['played'] ?? null,
                    'wins' => $entry['wins'] ?? null,
                    'losses' => $entry['losses'] ?? null,
                    'ties' => $entry['ties'] ?? null,
                    'additional_data' => [
                        'frame_wins' => $entry['frameWins'] ?? null,
                        'frame_losses' => $entry['frameLosses'] ?? null,
                        'frame_score' => $entry['frameScore'] ?? null,
                    ],
                ];
            })
            ->values()
            ->all();
    }
}