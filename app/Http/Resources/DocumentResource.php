<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DocumentResource extends JsonResource
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
            'discipline' => $this->getDisciplineSlug(),
            'discipline_id' => $this->discipline,
            'title' => $this->title,
            'file' => $this->file,
            'file_url' => asset($this->file),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    private function getDisciplineSlug(): ?string
    {
        return match ((int) $this->discipline) {
            1 => 'blackball',
            2 => 'carambole',
            3 => 'snooker',
            4 => 'americain',
            default => null,
        };
    }
}