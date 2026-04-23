<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PartnerResource;
use App\Models\Partenaire;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    //
    public function index(): JsonResponse
    {
        $partners = Partenaire::all();

        return response()->json([
            'count' => $partners->count(),
            'data' => PartnerResource::collection($partners),
            'error' => null,
        ]);
    }
}
