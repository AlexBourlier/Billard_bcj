<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PartnerResource;
use App\Models\Partenaire;
use Illuminate\Http\JsonResponse;

class PartnerController extends Controller
{
    /**
     * Liste des partenaires
     *
     * Retourne la liste des partenaires publics du club.
     *
     * @group Public
     *
     * @response 200 {
     *   "data": [
     *     {
     *       "id": 1,
     *       "name": "Tours Métropole",
     *       "logo": "partenaires/logo.png",
     *       "logo_url": "https://example.com/partenaires/logo.png",
     *       "website_url": "https://www.tours-metropole.fr",
     *       "created_at": "2025-05-26T14:21:37.000000Z",
     *       "updated_at": "2025-12-06T20:52:52.000000Z"
     *     }
     *   ],
     *   "meta": {
     *     "count": 1
     *   },
     *   "links": [],
     *   "error": null
     * }
     */
    public function index(): JsonResponse
    {
        $partners = Partenaire::visible()->get();

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