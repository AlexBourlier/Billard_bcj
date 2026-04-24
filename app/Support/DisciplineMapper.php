<?php

namespace App\Support;

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