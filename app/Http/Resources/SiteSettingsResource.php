<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SiteSettingsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'logo' => $this->logo,
            'logo_url' => $this->logo ? asset('uploads/' . $this->logo) : null,
            'banniere' => $this->banniere,
            'banniere_url' => $this->banniere ? asset('uploads/' . $this->banniere) : null,
            'adresse' => $this->adresse,
            'telephone' => $this->telephone,
            'email' => $this->email,
            'youtube_page' => $this->youtube_page,
            'facebook_page' => $this->facebook_page,
            'facebook_page_id' => $this->facebook_page_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}