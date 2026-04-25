<?php

namespace App\Services;

use App\Models\ClubMatchingRule;
use App\Models\CueScorePlayerMapping;
use App\Models\CueScoreRanking;
use Illuminate\Support\Collection;

/**
 * Service métier dédié à l'extraction des données club depuis les classements CueScore.
 *
 * Ce service ne récupère pas les données depuis CueScore directement.
 * Il travaille uniquement à partir des données déjà importées :
 * - rankings
 * - fetchs
 * - entries
 * - mappings joueurs/licenciés
 * - règles de matching club
 *
 * Il permet de produire des réponses exploitables par l'API publique.
 */
class CueScoreClubRankingService
{
    /**
     * Retourne le dernier fetch actif d'un classement CueScore.
     *
     * @param CueScoreRanking $ranking
     *
     * @return mixed
     */
    public function getActiveFetch(CueScoreRanking $ranking)
    {
        return $ranking->fetches()
            ->where('is_active', true)
            ->latest('id')
            ->first();
    }

    /**
     * Construit les données club pour un classement individuel.
     *
     * Seules les entrées correspondant à un joueur confirmé du club sont conservées.
     * Le lien entre un participant CueScore et un licencié local est déterminé via
     * CueScorePlayerMapping.
     *
     * @param CueScoreRanking $ranking Classement concerné
     * @param int $fetchId Identifiant du fetch utilisé
     * @param int|null $limit Nombre maximum d'entrées à retourner
     *
     * @return array{
     *     data: Collection,
     *     meta: array
     * }
     */
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

    /**
     * Construit les données club pour un classement par équipes.
     *
     * Contrairement aux classements individuels, toutes les équipes du classement
     * sont retournées uniquement si au moins une équipe du club est détectée.
     *
     * La détection repose sur les règles actives de ClubMatchingRule.
     *
     * @param CueScoreRanking $ranking Classement concerné
     * @param int $fetchId Identifiant du fetch utilisé
     *
     * @return array{
     *     data: Collection,
     *     meta: array{club_team_present: bool}
     * }
     */
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

    /**
     * Détermine si un nom d'équipe correspond aux règles de matching du club.
     *
     * Modes supportés :
     * - contains
     * - equals
     * - starts_with
     *
     * Les comparaisons sont effectuées après normalisation du texte.
     *
     * @param string|null $teamName Nom d'équipe CueScore
     * @param Collection $rules Règles actives de matching club
     *
     * @return bool
     */
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

    /**
     * Normalise un texte utilisé pour le matching club.
     *
     * La normalisation :
     * - convertit en minuscules
     * - supprime les accents courants
     * - remplace apostrophes et tirets par des espaces
     * - compacte les espaces multiples
     *
     * Cela permet de rendre le matching robuste face aux variations d'écriture
     * dans CueScore.
     *
     * @param string|null $value
     *
     * @return string
     */
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