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
     * Liste des contacts
     *
     * Retourne les informations de contact publiques du club.
     *
     * @group Public
     *
     * @response 200 {
     *   "data": [
     *     {
     *       "id": 1,
     *       "nom": "BCJ37",
     *       "email": "contact@bcj37.fr",
     *       "telephone": null
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