<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\ImageManager;

/**
 * Encodage WebP mutualise pour l'upload d'images (banniere, logo…).
 *
 * Centralise la meme logique que le pipeline existant (largeur maximale +
 * qualite) afin qu'elle soit testable et coherente d'un point d'entree a
 * l'autre.
 */
class ImageOptimizer
{
    /**
     * Retourne le contenu WebP d'une image, redimensionnee si elle depasse la
     * largeur maximale (les proportions sont conservees).
     *
     * @param  UploadedFile|string  $source  Fichier uploade ou chemin absolu.
     */
    public static function toWebp(UploadedFile|string $source, int $maxWidth, int $quality): string
    {
        $path = $source instanceof UploadedFile ? $source->getRealPath() : $source;

        $image = (new ImageManager(new GdDriver))->read($path);

        if ($image->width() > $maxWidth) {
            $image = $image->scale(width: $maxWidth);
        }

        return (string) $image->toWebp(quality: $quality);
    }
}
