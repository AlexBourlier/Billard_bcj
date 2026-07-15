<?php

namespace App\Services\CueScore;

use App\Models\CueScorePlayerMapping;
use App\Models\CueScoreRankingEntry;
use App\Models\Licencies;
use Illuminate\Support\Collection;

class CueScorePlayerMatcher
{
    public function __construct(
        private readonly CueScoreNameNormalizer $normalizer,
    ) {
    }

    /**
     * Rapproche une entree CueScore d'un licencie.
     *
     * @param Collection<int, array{licencie: Licencies, fullName: string}>|null $candidates
     *        Licencies pre-charges et normalises. Fournis par matchEntriesForFetch
     *        pour eviter de recharger/renormaliser la table a chaque entree.
     */
    public function matchEntry(CueScoreRankingEntry $entry, ?Collection $candidates = null): ?CueScorePlayerMapping
    {
        if ($entry->entry_type !== 'player' || empty($entry->participant_name) || empty($entry->participant_external_id)) {
            return null;
        }

        $candidates ??= $this->buildLicencieCandidates();

        $normalizedCueScoreName = $this->normalizer->normalize($entry->participant_name);

        $bestMatch = null;
        $bestScore = 0;
        $matchingMethod = null;

        foreach ($candidates as $candidate) {
            $fullName = $candidate['fullName'];

            if ($normalizedCueScoreName === $fullName) {
                $bestMatch = $candidate['licencie'];
                $bestScore = 100;
                $matchingMethod = 'exact_normalized';
                break;
            }

            similar_text($normalizedCueScoreName, $fullName, $percent);

            if ($percent > $bestScore) {
                $bestMatch = $candidate['licencie'];
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

        // Licencies charges et normalises une seule fois pour tout le fetch.
        $candidates = $this->buildLicencieCandidates();

        $count = 0;

        foreach ($entries as $entry) {
            $mapping = $this->matchEntry($entry, $candidates);

            if ($mapping !== null) {
                $count++;
            }
        }

        return $count;
    }

    /**
     * Charge tous les licencies et pre-calcule leur nom complet normalise.
     *
     * @return Collection<int, array{licencie: Licencies, fullName: string}>
     */
    private function buildLicencieCandidates(): Collection
    {
        return Licencies::query()->get()
            ->map(fn (Licencies $licencie) => [
                'licencie' => $licencie,
                'fullName' => $this->normalizer->normalizeFullName(
                    $licencie->prenom ?? null,
                    $licencie->nom ?? null
                ),
            ])
            ->filter(fn (array $candidate) => $candidate['fullName'] !== '')
            ->values();
    }
}
