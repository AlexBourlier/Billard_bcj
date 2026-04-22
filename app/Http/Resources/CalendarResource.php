<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CalendarResource extends JsonResource
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
            'discipline' => $this->discipline,
            'scope' => $this->scope,
            'name' => $this->name,
            'slug' => $this->slug,
            'source_type' => $this->source_type,
            'is_active' => $this->is_active,
            'display_name' => $this->display_name,
            'events_count' => $this->whenCounted('events'),
        ];
    }
}