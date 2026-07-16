<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InfoBlockResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'titre' => $this->titre,
            'resume' => $this->resume,
            'niveau' => $this->niveau,
            'lien' => $this->lien,
            'date_debut' => optional($this->date_debut)->toDateString(),
            'date_fin' => optional($this->date_fin)->toDateString(),
        ];
    }
}
