<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Support\DisciplineMapper;

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
            'discipline' => DisciplineMapper::slugFromId($this->discipline),
            'discipline_id' => $this->discipline,
            'year' => $this->year,
            'favoris' => (bool) $this->favoris,
            'image' => $this->thumbnail,
            'image_url' => $this->thumbnail ? asset('storage/' . $this->thumbnail) : null,
            'video' => $this->video,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
