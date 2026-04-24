<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LicenseImportSnapshot;
use App\Models\Licencies;
use Illuminate\Http\JsonResponse;

class LicenciesController extends Controller
{
    /**
     * Display a listing of licencies.
     */
    public function index(): JsonResponse
    {
        $licencies = Licencies::query()->get();

        return response()->json([
            'data' => $licencies,
            'meta' => [
                'count' => $licencies->count(),
            ],
            'links' => [],
            'error' => null,
        ]);
    }

    public function searchByName(string $name): JsonResponse
    {
        $licencies = Licencies::query()
            ->where(function ($query) use ($name) {
                $query->where('nom', 'like', '%' . $name . '%')
                    ->orWhere('prenom', 'like', '%' . $name . '%')
                    ->orWhere('licence', 'like', '%' . $name . '%');
            })
            ->select('prenom', 'nom', 'licence')
            ->distinct()
            ->get();

        return response()->json([
            'data' => $licencies,
            'meta' => [
                'search' => $name,
                'count' => $licencies->count(),
            ],
            'links' => [],
            'error' => null,
        ]);
    }

    /**
     * Display batches of license imports.
     */
    public function batches(): JsonResponse
    {
        $batches = LicenseImportSnapshot::query()
            ->select('import_batch_id')
            ->where('is_valid', true)
            ->distinct()
            ->get()
            ->pluck('import_batch_id');

        return response()->json([
            'data' => $batches,
            'meta' => [
                'count' => $batches->count(),
            ],
            'links' => [],
            'error' => null,
        ]);
    }
}