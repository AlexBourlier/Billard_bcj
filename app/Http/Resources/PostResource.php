<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
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
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'content' => $this->content,
            'discipline' => $this->getDisciplineSlug(),
            'discipline_id' => $this->discipline,
            'year' => $this->year,
            'favoris' => (bool) $this->favoris,
            'image' => $this->thumbnail,
            'image_url' => $this->thumbnail ? asset($this->thumbnail) : null,
            'video' => $this->video,
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