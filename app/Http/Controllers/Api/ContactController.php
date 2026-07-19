<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ContactResource;
use App\Models\Contact;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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

    /**
     * Envoie un message de contact.
     *
     * @group Public
     *
     * @bodyParam name string required Le nom de l'expéditeur. Example: John Doe
     * @bodyParam email string required L'email de l'expéditeur. Example: john.doe@example.com
     * @bodyParam message string required Le message de l'expéditeur. Example: Bonjour, je souhaite...
     *
     * @response 200 {
     *   "data": null,
     *   "meta": null,
     *   "links": [],
     *   "error": null
     * }
     */
    public function send(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                'not_regex:/<[^>]*>/',
            ],
            'email' => [
                'required',
                'email',
                'max:255',
            ],
            'message' => [
                'required',
                'string',
                'max:3000',
                'not_regex:/<[^>]*>/',
            ],
        ]);

        try {
            Mail::raw(
                "Nom : {$validated['name']}\n".
                "Email : {$validated['email']}\n\n".
                "Message :\n{$validated['message']}",
                function ($message) use ($validated) {
                    $message->from(config('mail.contact.from'), config('mail.contact.from_name'));
                    $message->to(config('mail.contact.to'));
                    $message->replyTo($validated['email'], $validated['name']);
                    $message->subject('Nouveau message depuis le formulaire de contact');
                }
            );

            return response()->json([
                'data' => [
                    'message' => 'Votre message a bien été envoyé.',
                ],
                'meta' => [],
                'links' => [],
                'error' => null,
            ]);
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'data' => null,
                'meta' => [],
                'links' => [],
                'error' => "Une erreur est survenue lors de l'envoi du message.",
            ], 500);
        }
    }
}
