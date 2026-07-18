<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class SiteSettingsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        [$bannW, $bannH] = $this->imageDimensions($this->banniere);

        return [
            'id' => $this->id,
            'logo' => $this->logo,
            'logo_url' => $this->logo ? asset('storage/'.$this->logo) : null,
            'banniere' => $this->banniere,
            'banniere_url' => $this->banniere ? asset('storage/'.$this->banniere) : null,
            // Dimensions reelles de la banniere : permettent au front de reserver
            // le bon espace (element LCP) sans deformer l'image ni provoquer de
            // decalage de mise en page.
            'banniere_width' => $bannW,
            'banniere_height' => $bannH,
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

    /**
     * Dimensions (largeur, hauteur) d'une image stockee sur le disque public.
     * Retourne [null, null] si le chemin est vide ou le fichier introuvable.
     * L'appel n'a lieu qu'au (re)calcul de la reponse publique, elle-meme mise
     * en cache.
     *
     * @return array{0: int|null, 1: int|null}
     */
    private function imageDimensions(?string $path): array
    {
        if (! $path) {
            return [null, null];
        }

        $full = Storage::disk('public')->path($path);
        if (! is_file($full)) {
            return [null, null];
        }

        $size = @getimagesize($full);

        return $size ? [(int) $size[0], (int) $size[1]] : [null, null];
    }
}
