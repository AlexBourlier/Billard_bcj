<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\IndexResource;
use App\Http\Resources\InfoBlockResource;
use App\Http\Resources\MenuResource;
use App\Http\Resources\PartnerResource;
use App\Http\Resources\PostResource;
use App\Http\Resources\SiteSettingsResource;
use App\Models\Index;
use App\Models\InfoBlock;
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
     * Informations globales du site
     *
     * Retourne les informations générales nécessaires au front public :
     * paramètres du site et menus actifs.
     *
     * @group Public
     *
     * @response 200 {
     *   "data": {
     *     "site_settings": {
     *       "id": 1,
     *       "logo": "img/logo.png",
     *       "logo_url": "https://example.com/img/logo.png",
     *       "banniere": "img/banner.png",
     *       "banniere_url": "https://example.com/img/banner.png",
     *       "adresse": "28 Rue Joseph Cugnot, 37300 Joué-lès-Tours",
     *       "telephone": null,
     *       "email": "contact@bcj37.fr",
     *       "youtube_page": "https://www.youtube.com/@BCJ37",
     *       "facebook_page": "https://www.facebook.com/example",
     *       "facebook_page_id": "123456789",
     *       "created_at": "2025-05-13T10:22:11.000000Z",
     *       "updated_at": "2025-08-21T15:12:15.000000Z"
     *     },
     *     "menus": [
     *       {
     *         "id": 1,
     *         "name": "blackball",
     *         "image": "menu/blackball.png",
     *         "image_url": "https://example.com/menu/blackball.png",
     *         "actif": true,
     *         "created_at": null,
     *         "updated_at": "2025-05-29T17:06:39.000000Z"
     *       }
     *     ]
     *   },
     *   "meta": {
     *     "menus_count": 1
     *   },
     *   "links": [],
     *   "error": null
     * }
     */
    public function site(): JsonResponse
    {
        $siteSettings = SiteSetting::query()->first();

        $menus = Menu::query()
            ->where('actif', true)
            ->orderBy('id')
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
     * Données de la page d'accueil
     *
     * Retourne les données nécessaires à l’affichage de la page d’accueil publique.
     *
     * @group Public
     *
     * La réponse est mise en cache pendant 10 minutes.
     *
     * L’article mis en avant correspond au dernier article favori.
     * Si aucun article favori n’existe, le dernier article publié est utilisé en fallback.
     *
     * @response 200 {
     *   "data": {
     *     "site_settings": {
     *       "id": 1,
     *       "logo": "img/logo.png",
     *       "logo_url": "https://example.com/img/logo.png",
     *       "banniere": "img/banner.png",
     *       "banniere_url": "https://example.com/img/banner.png",
     *       "adresse": "28 Rue Joseph Cugnot, 37300 Joué-lès-Tours",
     *       "telephone": null,
     *       "email": "contact@bcj37.fr",
     *       "youtube_page": "https://www.youtube.com/@BCJ37",
     *       "facebook_page": "https://www.facebook.com/example",
     *       "facebook_page_id": "123456789",
     *       "created_at": "2025-05-13T10:22:11.000000Z",
     *       "updated_at": "2025-08-21T15:12:15.000000Z"
     *     },
     *     "menus": [
     *       {
     *         "id": 1,
     *         "name": "blackball",
     *         "image": "menu/blackball.png",
     *         "image_url": "https://example.com/menu/blackball.png",
     *         "actif": true,
     *         "created_at": null,
     *         "updated_at": "2025-05-29T17:06:39.000000Z"
     *       }
     *     ],
     *     "partners": [
     *       {
     *         "id": 1,
     *         "name": "Tours Métropole",
     *         "logo": "partenaires/logo.png",
     *         "logo_url": "https://example.com/partenaires/logo.png",
     *         "website_url": "https://www.tours-metropole.fr",
     *         "created_at": "2025-05-26T14:21:37.000000Z",
     *         "updated_at": "2025-12-06T20:52:52.000000Z"
     *       }
     *     ],
     *     "featured_post": {
     *       "id": 1,
     *       "title": "Titre de l'article",
     *       "slug": "titre-de-l-article",
     *       "excerpt": "Résumé de l'article...",
     *       "content": "<p>Contenu HTML de l'article</p>",
     *       "discipline": "blackball",
     *       "discipline_id": 1,
     *       "year": 2026,
     *       "favoris": true,
     *       "image": null,
     *       "image_url": null,
     *       "video": null,
     *       "created_at": "2026-02-02T08:11:13.000000Z",
     *       "updated_at": "2026-02-02T08:31:49.000000Z"
     *     }
     *   },
     *   "meta": {
     *     "menus_count": 1,
     *     "partners_count": 1,
     *     "has_featured_post": true
     *   },
     *   "links": [],
     *   "error": null
     * }
     */
    public function home(): JsonResponse
    {
        // La page d'accueil est la plus consultee : on met sa charge utile en
        // cache court (10 min). Le cache est de toute facon invalide des qu'un
        // contenu concerne change (ex : ecriture d'un partenaire), donc cette
        // duree ne fait que borner la fraicheur en l'absence de modification.
        $response = Cache::remember(
            CacheKeys::publicHome(),
            now()->addMinutes(10),
            function () {
                $siteSettings = SiteSetting::query()->first();

                $menus = Menu::query()
                    ->where('actif', true)
                    ->orderBy('id')
                    ->get();

                $partners = Partenaire::visible()->get();

                $infoBlocks = InfoBlock::visible()->get();

                $featuredPost = Post::query()
                    ->published()
                    ->where('favoris', true)
                    ->orderByDesc('created_at')
                    ->first();

                $index = Index::query()->first();

                if (! $featuredPost) {
                    $featuredPost = Post::query()
                        ->published()
                        ->orderByDesc('created_at')
                        ->first();
                }

                return [
                    'data' => [
                        'site_settings' => $siteSettings ? new SiteSettingsResource($siteSettings) : null,
                        'menus' => MenuResource::collection($menus),
                        'partners' => PartnerResource::collection($partners),
                        'info_blocks' => InfoBlockResource::collection($infoBlocks),
                        'featured_post' => $featuredPost ? new PostResource($featuredPost) : null,
                        'welcome_message' => $index ? new IndexResource($index) : null,
                        'site' => [
                            'logo_url' => asset('img/h2eb.png'),
                        ],
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
