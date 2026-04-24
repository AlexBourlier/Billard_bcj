<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MenuResource;
use App\Http\Resources\PartnerResource;
use App\Http\Resources\PostResource;
use App\Http\Resources\SiteSettingsResource;
use App\Models\Menu;
use App\Models\Partenaire;
use App\Models\Post;
use App\Models\SiteSetting;
use Illuminate\Http\JsonResponse;

class PublicController extends Controller
{
    public function site(): JsonResponse
    {
        $siteSettings = SiteSetting::query()->first();

        $menus = Menu::query()
            ->where('actif', true)
            ->orderBy('nom')
            ->get();

        return response()->json([
            'data' => [
                'site_settings' => $siteSettings ? new SiteSettingsResource($siteSettings) : null,
                'menus' => MenuResource::collection($menus),
            ],
            'meta' => [
                'menus_count' => $menus->count(),
            ],
            'links' => [],
            'error' => null,
        ]);
    }

    public function home(): JsonResponse
    {
        $siteSettings = SiteSetting::query()->first();

        $menus = Menu::query()
            ->where('actif', true)
            ->orderBy('id')
            ->get();

        $partners = Partenaire::query()
            ->orderBy('id')
            ->get();

        $featuredPost = Post::query()
            ->where('favoris', true)
            ->orderByDesc('created_at')
            ->first();

        if (!$featuredPost) {
            $featuredPost = Post::query()
                ->orderByDesc('created_at')
                ->first();
        }

        return response()->json([
            'data' => [
                'site_settings' => $siteSettings ? new SiteSettingsResource($siteSettings) : null,
                'menus' => MenuResource::collection($menus),
                'partners' => PartnerResource::collection($partners),
                'featured_post' => $featuredPost ? new PostResource($featuredPost) : null,
            ],
            'meta' => [
                'menus_count' => $menus->count(),
                'partners_count' => $partners->count(),
                'has_featured_post' => $featuredPost !== null,
            ],
            'links' => [],
            'error' => null,
        ]);
    }
}