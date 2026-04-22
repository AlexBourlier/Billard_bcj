<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CalendarEventResource extends JsonResource
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
            'external_id' => $this->external_id,
            'date_debut' => $this->date_debut?->toISOString(),
            'date_fin' => $this->date_fin?->toISOString(),
            'date_limite' => $this->date_limite?->toISOString(),
            'titre' => $this->titre,
            'lieu' => $this->lieu,
            'club' => $this->club,
            'url' => $this->url,
            'status' => $this->status,
            'is_upcoming' => $this->is_upcoming,
            'is_past' => $this->is_past,
            'has_registration_deadline' => $this->has_registration_deadline,
            'links' => CalendarEventLinkResource::collection($this->whenLoaded('links')),
        ];
    }
}