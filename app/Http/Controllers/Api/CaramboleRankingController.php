<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Http\JsonResponse;

class CaramboleRankingController extends Controller
{
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