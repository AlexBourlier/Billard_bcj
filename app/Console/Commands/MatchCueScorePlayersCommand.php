<?php

namespace App\Console\Commands;

use App\Services\CueScore\CueScorePlayerMatcher;
use Illuminate\Console\Command;

class MatchCueScorePlayersCommand extends Command
{
    protected $signature = 'cuescore:match {fetchId : ID du fetch CueScore}';
    protected $description = 'Match CueScore players with local licencies';

    public function handle(CueScorePlayerMatcher $matcher): int
    {
        $fetchId = (int) $this->argument('fetchId');

        $count = $matcher->matchEntriesForFetch($fetchId);

        $this->info(sprintf('%d mapping(s) traité(s).', $count));

        return self::SUCCESS;
    }
}