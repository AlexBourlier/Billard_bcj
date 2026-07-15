<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

/**
 * Execute l'import CueScore (cuescore:import --with-match --active-only) en
 * arriere-plan, pour ne pas bloquer la requete admin. Optionnellement cible une
 * discipline.
 */
class RunCueScoreImportJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $timeout = 900;

    public function __construct(
        public ?string $discipline = null,
    ) {
    }

    public function handle(): void
    {
        $params = [
            '--with-match'  => true,
            '--active-only' => true,
        ];

        if ($this->discipline) {
            $params['--discipline'] = $this->discipline;
        }

        Artisan::call('cuescore:import', $params);

        Log::info('cuescore.import.job.completed', [
            'discipline' => $this->discipline,
            'output' => trim(Artisan::output()),
        ]);
    }
}
