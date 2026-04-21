<?php

namespace App\Services\CueScore;

use App\Models\CueScorePlayerMapping;
use App\Models\CueScoreRankingEntry;
use App\Models\Licencies;

class CueScorePlayerMatcher
{
    public function __construct(
        private readonly CueScoreNameNormalizer $normalizer,
    ) {
    }

    public function matchEntry(CueScoreRankingEntry $entry): ?CueScorePlayerMapping
    {
        if ($entry->entry_type !== 'player' || empty($entry->participant_name) || empty($entry->participant_external_id)) {
            return null;
        }

        $normalizedCueScoreName = $this->normalizer->normalize($entry->participant_name);

        $licencies = Licencies::query()->get();

        $bestMatch = null;
        $bestScore = 0;
        $matchingMethod = null;

        foreach ($licencies as $licencie) {
            $fullName = $this->normalizer->normalizeFullName(
                $licencie->prenom ?? null,
                $licencie->nom ?? null
            );

            if ($fullName === '') {
                continue;
            }

            if ($normalizedCueScoreName === $fullName) {
                $bestMatch = $licencie;
                $bestScore = 100;
                $matchingMethod = 'exact_normalized';
                break;
            }

            similar_text($normalizedCueScoreName, $fullName, $percent);

            if ($percent > $bestScore) {
                $bestMatch = $licencie;
                $bestScore = (int) round($percent);
                $matchingMethod = 'similarity';
            }
        }

        if ($bestMatch === null || $bestScore < 85) {
            return CueScorePlayerMapping::updateOrCreate(
                [
                    'cuescore_participant_id' => (string) $entry->participant_external_id,
                ],
                [
                    'cuescore_name' => $entry->participant_name,
                    'cuescore_url' => $entry->participant_url,
                    'licencie_id' => null,
                    'matching_method' => $matchingMethod ?? 'unmatched',
                    'confidence_score' => $bestScore ?: null,
                    'is_confirmed' => false,
                    'notes' => 'Aucune correspondance fiable trouvée automatiquement.',
                ]
            );
        }

        return CueScorePlayerMapping::updateOrCreate(
            [
                'cuescore_participant_id' => (string) $entry->participant_external_id,
            ],
            [
                'cuescore_name' => $entry->participant_name,
                'cuescore_url' => $entry->participant_url,
                'licencie_id' => $bestMatch->id,
                'matching_method' => $matchingMethod,
                'confidence_score' => $bestScore,
                'is_confirmed' => $bestScore >= 95,
                'notes' => $bestScore >= 95
                    ? 'Correspondance automatique fiable.'
                    : 'Correspondance automatique à vérifier manuellement.',
            ]
        );
    }

    public function matchEntriesForFetch(int $fetchId): int
    {
        $entries = CueScoreRankingEntry::query()
            ->where('cuescore_ranking_fetch_id', $fetchId)
            ->where('entry_type', 'player')
            ->get();

        $count = 0;

        foreach ($entries as $entry) {
            $mapping = $this->matchEntry($entry);

            if ($mapping !== null) {
                $count++;
            }
        }

        return $count;
    }
}