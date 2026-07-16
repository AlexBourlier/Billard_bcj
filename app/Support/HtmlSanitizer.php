<?php

namespace App\Support;

/**
 * Nettoyage du HTML des contenus editoriaux (articles, message d'accueil).
 *
 * Le frontend React rend ces contenus en HTML brut (dangerouslySetInnerHTML) :
 * ils doivent donc etre nettoyes cote serveur selon une liste blanche stricte
 * (profil « post » de config/purifier.php) avant d'etre exposes par l'API.
 *
 * Le nettoyage est applique a la sortie (Resources) : il couvre l'ensemble des
 * articles existants sans modifier la base et reste donc totalement reversible.
 */
class HtmlSanitizer
{
    public static function post(?string $html): ?string
    {
        if ($html === null || trim($html) === '') {
            return $html;
        }

        return clean($html, 'post');
    }
}
