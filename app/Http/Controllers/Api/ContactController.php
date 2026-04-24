<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ContactResource;
use App\Models\Contact;
use Illuminate\Http\JsonResponse;

class ContactController extends Controller
{
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