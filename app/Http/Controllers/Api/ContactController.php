<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ContactResource;
use App\Models\Contact;

class ContactController extends Controller
{
    //
    public function index(){

        $contact = Contact::all();
    
        return response()->json([
            'count' => $contact->count(),
            'data' => ContactResource::collection($contact),
            'error' => null,
        ]);
    }
}
