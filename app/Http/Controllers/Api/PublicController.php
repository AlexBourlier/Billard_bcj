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
use App\Support\CacheKeys;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

/**
 * Contrôleur API pour les données publiques globales du site.
 *
 * Expose les informations nécessaires aux pages publiques :
 * - paramètres du site
 * - menus actifs
 * - partenaires
 * - article mis en avant
 *
 * Les réponses respectent le format standard :
 * data / meta / links / error
 */
class PublicController extends Controller
{
    /**
     * Retourne les informations globales du site.
     *
     * Inclut :
     * - paramètres du site
     * - menus actifs
     *
     * @return JsonResponse
     */
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

    /**
     * Retourne les données nécessaires à la page d’accueil publique.
     *
     * La réponse est mise en cache pendant 10 minutes.
     *
     * Inclut :
     * - paramètres du site
     * - menus actifs
     * - partenaires
     * - article favori ou dernier article publié en fallback
     *
     * @return JsonResponse
     */
    public function home(): JsonResponse
    {
        $response = Cache::remember(
            CacheKeys::publicHome(),
            now()->addMinutes(10),
            function () {
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

                return [
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
                ];
            }
        );

        return response()->json($response);
    }
}