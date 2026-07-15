<?php

namespace App\Support;

use App\Models\Licencies;

/**
 * Fournit la liste des noms complets des licencies du club.
 *
 * Remplace l'ancien fichier public/script/licencies.txt (genere par le script
 * Python legacy). La source est desormais la table `licencies`, alimentee
 * exclusivement par le pipeline d'import Telemat.
 */
class LicencieNames
{
    /**
     * Liste "Prenom Nom" de tous les licencies, utilisee cote client pour
     * reperer les inscrits CueScore qui sont licencies du club.
     *
     * @return string[]
     */
    public static function fullNames(): array
    {
        return Licencies::query()
            ->orderBy('nom')
            ->orderBy('prenom')
            ->get(['nom', 'prenom'])
            ->map(fn ($l) => trim(($l->prenom ?? '') . ' ' . ($l->nom ?? '')))
            ->filter(fn ($name) => $name !== '')
            ->values()
            ->all();
    }
}
