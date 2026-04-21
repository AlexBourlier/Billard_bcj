<?php

namespace App\Console\Commands;

use App\Models\CueScoreRanking;
use App\Services\CueScore\CueScorePlayerMatcher;
use App\Services\CueScore\CueScoreRankingImporter;
use Illuminate\Console\Command;

class ImportCueScoreCommand extends Command
{
    protected $signature = 'cuescore:import 
                            {rankingId? : ID interne de cuescore_rankings}
                            {--discipline= : Filtrer par discipline}
                            {--active-only : Importer uniquement les classements actifs}
                            {--with-match : Lancer automatiquement le matching après import}';

    protected $description = 'Import CueScore rankings and tournaments';

    public function handle(
        CueScoreRankingImporter $importer,
        CueScorePlayerMatcher $matcher
    ): int {
        $query = CueScoreRanking::query()->orderBy('sort_order');

        if ($rankingId = $this->argument('rankingId')) {
            $query->whereKey($rankingId);
        }

        if ($discipline = $this->option('discipline')) {
            $query->where('discipline', $discipline);
        }

        if ($this->option('active-only')) {
            $query->where('is_active', true);
        }

        $rankings = $query->get();

        if ($rankings->isEmpty()) {
            $this->warn('Aucun classement CueScore trouvé.');
            return self::SUCCESS;
        }

        $this->info(sprintf('%d classement(s) à importer.', $rankings->count()));

        foreach ($rankings as $ranking) {
            $this->line(sprintf(
                'Import de [%s] (%s / %s)',
                $ranking->name,
                $ranking->source_type,
                $ranking->cuescore_id
            ));

            try {
                $fetch = $importer->import($ranking);

                $this->info(sprintf(
                    'OK - fetch #%d - status=%s - records=%d',
                    $fetch->id,
                    $fetch->status,
                    $fetch->records_count
                ));

                if ($ranking->ranking_type === 'individual' && $fetch->status === 'success') {
                    $matched = $matcher->matchEntriesForFetch($fetch->id);

                    $this->info(sprintf(
                        'Matching OK - fetch #%d - mappings traités=%d',
                        $fetch->id,
                        $matched
                    ));
                }
            } catch (\Throwable $e) {
                $this->error(sprintf(
                    'Erreur sur [%s] : %s',
                    $ranking->name,
                    $e->getMessage()
                ));
            }
        }

        return self::SUCCESS;
    }
}