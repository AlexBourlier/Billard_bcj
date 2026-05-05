<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Support\DisciplineMapper;

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
            'discipline' => DisciplineMapper::slugFromId($this->discipline),
            'discipline_id' => $this->discipline,
            'title' => $this->title,
            'file' => $this->file,
            'file_url' => asset('storage/' . $this->file),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

}