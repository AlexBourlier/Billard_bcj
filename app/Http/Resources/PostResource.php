<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Support\DisciplineMapper;
use App\Support\HtmlSanitizer;

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
            'excerpt' => HtmlSanitizer::post($this->excerpt),
            'content' => HtmlSanitizer::post($this->content),
            'discipline' => DisciplineMapper::slugFromId($this->discipline),
            'discipline_id' => $this->discipline,
            'year' => $this->year,
            'favoris' => (bool) $this->favoris,
            'image' => $this->thumbnail,

            'image_url' => $this->thumbnail
                ? asset('storage/' . $this->thumbnail)
                : null,

            'image_thumb_url' => $this->thumbnail
                ? asset('storage/thumbs/' . pathinfo($this->thumbnail, PATHINFO_FILENAME) . '.webp')
                : null,
            'video' => $this->video,
            'video_url'=> $this->video,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
