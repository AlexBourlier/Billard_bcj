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

        $featuredPosts = Post::query()
            ->where('favoris', true)
            ->orderByDesc('created_at')
            ->first();

        if (!$featuredPosts) {
            $featuredPosts = Post::query()
                ->orderByDesc('created_at')
                ->first();
        }


        return response()->json([
            'data' => [
                'site_settings' => $siteSettings ? new SiteSettingsResource($siteSettings) : null,
                'menus' => MenuResource::collection($menus),
                'partners' => PartnerResource::collection($partners),
                'featured_posts' => $featuredPosts ? new PostResource($featuredPosts) : null,
            ],
            'error' => null,
        ]);
    }
}