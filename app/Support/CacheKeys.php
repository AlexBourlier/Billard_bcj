<?php

namespace App\Support;

class CacheKeys
{
    public static function publicHome(): string
    {
        return 'public_home';
    }

    public static function discipline(string $slug): string
    {
        return "discipline:{$slug}";
    }

    public static function rankingsPreview(string $slug, int $limit): string
    {
        return "discipline:{$slug}:rankings_preview:limit:{$limit}";
    }
}