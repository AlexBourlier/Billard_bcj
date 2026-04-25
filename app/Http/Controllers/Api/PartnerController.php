<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PartnerResource;
use App\Models\Partenaire;
use Illuminate\Http\JsonResponse;

class PartnerController extends Controller
{
    /**
     * Retourne la liste de tous les partenaires.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $partners = Partenaire::all();

        return response()->json([
            'data' => PartnerResource::collection($partners),
            'meta' => [
                'count' => $partners->count(),
            ],
            'links' => [],
            'error' => null,
        ]);
    }
}