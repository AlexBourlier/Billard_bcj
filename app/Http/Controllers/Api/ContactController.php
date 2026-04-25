<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ContactResource;
use App\Models\Contact;
use Illuminate\Http\JsonResponse;

/**
 * Contrôleur API pour la gestion des contacts.
 *
 * Expose les informations de contact publiques du club.
 *
 * Les réponses respectent le format standard :
 * data / meta / links / error
 */
class ContactController extends Controller
{
    /**
     * Retourne la liste des contacts disponibles.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $contact = Contact::all();

        return response()->json([
            'data' => ContactResource::collection($contact),
            'meta' => [
                'count' => $contact->count(),
            ],
            'links' => [],
            'error' => null,
        ]);
    }
}