<?php

namespace App\Services\CueScore;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class CueScoreApiClient
{
    private const BASE_URL = 'https://api.cuescore.com';

    public function fetchRanking(string $rankingId): Response
    {
        return Http::timeout(30)->get(self::BASE_URL . '/ranking/', [
            'id' => $rankingId,
        ]);
    }

    public function fetchTournamentResults(string $tournamentId): Response
    {
        return Http::timeout(30)->get(self::BASE_URL . '/tournament/', [
            'id' => $tournamentId,
            'results' => 'Result list',
        ]);
    }

    public function fetchTournamentStandings(string $tournamentId): Response
    {
        return Http::timeout(30)->get(self::BASE_URL . '/tournament/', [
            'id' => $tournamentId,
        ]);
    }
}