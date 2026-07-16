<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Http\JsonResponse;

/**
 * @group Classements
 */
class CaramboleRankingController extends Controller
{
    /**
     * Classements carambole (PDF)
     *
     * Liste les fichiers PDF de classement carambole mis a disposition.
     *
     * @response 200 {
     *   "data": [{"name": "3 bandes", "filename": "3 bandes.pdf", "url": "https://bcj37.fr/ftp/3 bandes.pdf"}],
     *   "meta": {"count": 1},
     *   "links": [],
     *   "error": null
     * }
     * @response 404 {"data": [], "meta": [], "links": [], "error": "Dossier ftp introuvable"}
     */
    public function index(): JsonResponse
    {
        $directory = public_path('ftp');

        if (! File::exists($directory)) {
            return response()->json([
                'data' => [],
                'meta' => [],
                'links' => [],
                'error' => 'Dossier ftp introuvable',
            ], 404);
        }

        $files = collect(File::files($directory))
            ->filter(fn ($file) => strtolower($file->getExtension()) === 'pdf')
            ->map(function ($file) {
                $filename = $file->getFilename();

                return [
                    'name' => pathinfo($filename, PATHINFO_FILENAME),
                    'filename' => $filename,
                    'url' => asset('ftp/' . $filename),
                ];
            })
            ->sortBy('name')
            ->values();

        return response()->json([
            'data' => $files,
            'meta' => [
                'count' => $files->count(),
            ],
            'links' => [],
            'error' => null,
        ]);
    }
}