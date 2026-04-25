<?php

namespace App\Services\CueScore;

use App\Models\CueScoreRanking;
use App\Models\CueScoreRankingEntry;
use App\Models\CueScoreRankingFetch;
use App\Support\ApiCacheInvalidator;
use App\Support\ApiCacheWarmer;
use Illuminate\Support\Facades\DB;

/**
 * Service responsable de l'import d'un classement CueScore.
 *
 * Ce service :
 * - récupère les données depuis l'API CueScore
 * - sélectionne le bon parseur selon le type de classement
 * - désactive les anciens fetchs actifs
 * - crée un nouveau fetch
 * - stocke les entrées associées
 * - invalide puis préchauffe le cache API après un import réussi
 */
class CueScoreRankingImporter
{
    public function __construct(
        private readonly CueScoreApiClient $client,
        private readonly CueScoreEntryParser $parser,
        private readonly ApiCacheInvalidator $cacheInvalidator,
        private readonly ApiCacheWarmer $cacheWarmer,
    ) {
    }

    /**
     * Importe les données d'un classement CueScore.
     *
     * La stratégie dépend de la source et du type de classement :
     * - ranking + individual : participants d'un classement
     * - tournament + team : standings d'équipes
     * - tournament + individual : résultats individuels de tournoi
     *
     * L'opération d'écriture est transactionnelle :
     * - les anciens fetchs sont désactivés
     * - le nouveau fetch est créé
     * - les entrées sont insérées
     *
     * En cas d'import réussi, le cache public lié à la discipline est invalidé
     * puis le preview des classements est préchauffé.
     *
     * @param CueScoreRanking $ranking Classement à importer
     *
     * @return CueScoreRankingFetch Fetch créé pour cet import
     *
     * @throws \RuntimeException Lorsque le type de classement CueScore n'est pas supporté
     */
    public function import(CueScoreRanking $ranking): CueScoreRankingFetch
    {
        $response = $ranking->source_type === 'ranking'
            ? $this->client->fetchRanking($ranking->cuescore_id)
            : $this->client->fetchTournamentStandings($ranking->cuescore_id);

        $payload = $response->json();

        if ($ranking->source_type === 'ranking' && $ranking->ranking_type === 'individual') {
            $response = $this->client->fetchRanking($ranking->cuescore_id);
            $payload = $response->json();
            $entries = $this->parser->parseRankingParticipants($payload);
        } elseif ($ranking->source_type === 'tournament' && $ranking->ranking_type === 'team') {
            $response = $this->client->fetchTournamentStandings($ranking->cuescore_id);
            $payload = $response->json();
            $entries = $this->parser->parseTournamentStandings($payload);
        } elseif ($ranking->source_type === 'tournament' && $ranking->ranking_type === 'individual') {
            $response = $this->client->fetchTournamentResults($ranking->cuescore_id);
            $payload = $response->json();
            $entries = $this->parser->parseTournamentResults($payload);
        } else {
            throw new \RuntimeException('Type de classement CueScore non supporté.');
        }

        return DB::transaction(function () use ($ranking, $response, $payload, $entries) {
            CueScoreRankingFetch::where('cuescore_ranking_id', $ranking->id)
                ->update(['is_active' => false]);

            $fetch = CueScoreRankingFetch::create([
                'cuescore_ranking_id' => $ranking->id,
                'status' => $response->successful() ? 'success' : 'failed',
                'fetched_at' => now(),
                'http_status' => $response->status(),
                'payload_hash' => hash('sha256', json_encode($payload)),
                'records_count' => count($entries),
                'error_code' => $response->successful() ? null : (string) $response->status(),
                'error_message' => $response->successful() ? null : 'CueScore API request failed',
                'raw_payload' => json_encode($payload, JSON_UNESCAPED_UNICODE),
                'is_active' => $response->successful(),
            ]);

            foreach ($entries as $entry) {
                CueScoreRankingEntry::create([
                    'cuescore_ranking_id' => $ranking->id,
                    'cuescore_ranking_fetch_id' => $fetch->id,
                    ...$entry,
                ]);
            }

            if ($fetch->is_active) {
                $this->cacheInvalidator->disciplinePage($ranking->discipline);
                $this->cacheWarmer->disciplinePage($ranking->discipline);
            }

            return $fetch;
        });
    }
}