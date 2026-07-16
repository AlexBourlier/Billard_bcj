<?php

namespace App\Support;

/**
 * Correspondance entre le slug d'une discipline (utilise cote API/frontend) et
 * son code entier stocke en base.
 *
 * Historiquement, les tables `posts` et `documents` stockent la discipline sous
 * forme d'entier (1 a 4) et non de chaine. Cette classe centralise cette
 * convention pour eviter que les codes soient recopies un peu partout.
 * L'absence de correspondance (code 0/null) designe une actualite generale du
 * club, sans discipline.
 */
class DisciplineMapper
{
    public const DISCIPLINES = [
        'blackball' => 1,
        'carambole' => 2,
        'snooker' => 3,
        'americain' => 4,
    ];

    public static function idFromSlug(string $slug): ?int
    {
        return self::DISCIPLINES[$slug] ?? null;
    }

    public static function slugFromId(int|string|null $id): ?string
    {
        if ($id === null) {
            return null;
        }

        $id = (int) $id;

        return array_flip(self::DISCIPLINES)[$id] ?? null;
    }

    public static function isValidSlug(string $slug): bool
    {
        return array_key_exists($slug, self::DISCIPLINES);
    }

    public static function slugs(): array
    {
        return array_keys(self::DISCIPLINES);
    }
}