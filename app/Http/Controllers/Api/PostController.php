<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Support\DisciplineMapper;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Contrôleur API pour la gestion des articles (posts).
 *
 * Permet de :
 * - lister les articles (pagination)
 * - récupérer un article
 * - filtrer par discipline, année, décennie
 * - récupérer les favoris
 *
 * Les réponses respectent le format standard :
 * data / meta / links / error
 */
class PostController extends Controller
{
    /**
     * Articles du club
     *
     * Retourne la liste paginée des articles du club (hors disciplines), triés du
     * plus récent au plus ancien. Alimente la page « Vie du club ».
     *
     * @group Articles
     *
     * @queryParam per_page integer Nombre d’articles par page. Min: 1. Max: 100. Default: 10. Exemple : 10
     *
     * @response 200 {
     *   "data": [],
     *   "meta": {
     *     "count": 25,
     *     "current_page": 1,
     *     "last_page": 3,
     *     "per_page": 10,
     *     "from": 1,
     *     "to": 10,
     *     "total": 25
     *   },
     *   "links": {
     *     "first": "http://127.0.0.1:8000/api/v1/posts?page=1",
     *     "last": "http://127.0.0.1:8000/api/v1/posts?page=3",
     *     "prev": null,
     *     "next": "http://127.0.0.1:8000/api/v1/posts?page=2"
     *   },
     *   "error": null
     * }
     */
    public function index(): JsonResponse
    {
        // Page « Vie du club » : uniquement les articles du club (hors disciplines).
        $posts = Post::query()
            ->published()
            ->club()
            ->orderByDesc('created_at')
            ->paginate($this->getPerPage());

        return $this->paginatedResponse($posts);
    }

    /**
     * Détail d’un article
     *
     * Retourne un article à partir de son identifiant.
     *
     * @group Articles
     *
     * @urlParam id integer required Identifiant de l’article. Exemple : 1
     *
     * @response 200 {
     *   "data": {
     *     "id": 1,
     *     "title": "Titre de l'article",
     *     "slug": "titre-de-l-article",
     *     "excerpt": "Résumé de l'article...",
     *     "content": "<p>Contenu HTML de l'article</p>",
     *     "discipline": "blackball",
     *     "discipline_id": 1,
     *     "year": 2026,
     *     "favoris": true,
     *     "image": null,
     *     "image_url": null,
     *     "video": null,
     *     "created_at": "2026-02-02T08:11:13.000000Z",
     *     "updated_at": "2026-02-02T08:31:49.000000Z"
     *   },
     *   "meta": [],
     *   "links": [],
     *   "error": null
     * }
     */
    public function show(int $id): JsonResponse
    {
        $post = Post::query()->published()->find($id);

        if (! $post) {
            return response()->json([
                'data' => null,
                'meta' => [],
                'links' => [],
                'error' => [
                    'code' => 'post_not_found',
                    'message' => 'Article not found',
                ],
            ], 404);
        }

        return $this->singleResponse($post);
    }

    /**
     * Articles par discipline
     *
     * Retourne la liste paginée des articles associés à une discipline.
     *
     * @group Articles
     *
     * @urlParam discipline string required Slug de la discipline. Exemple : blackball
     *
     * @queryParam per_page integer Nombre d’articles par page. Min: 1. Max: 100. Default: 10. Exemple : 10
     *
     * @response 200 {
     *   "data": [],
     *   "meta": {
     *     "discipline": "blackball",
     *     "count": 12,
     *     "current_page": 1,
     *     "last_page": 2,
     *     "per_page": 10,
     *     "from": 1,
     *     "to": 10,
     *     "total": 12
     *   },
     *   "links": [],
     *   "error": null
     * }
     * @response 404 {
     *   "data": null,
     *   "meta": [],
     *   "links": [],
     *   "error": {
     *     "code": "discipline_not_found",
     *     "message": "Discipline not found"
     *   }
     * }
     */
    public function getPostsByDiscipline(string $discipline): JsonResponse
    {
        $disciplineId = DisciplineMapper::idFromSlug($discipline);

        if ($disciplineId === null) {
            return response()->json([
                'data' => null,
                'meta' => [],
                'links' => [],
                'error' => [
                    'code' => 'discipline_not_found',
                    'message' => 'Discipline not found',
                ],
            ], 404);
        }

        $posts = Post::query()
            ->published()
            ->where('discipline', $disciplineId)
            ->orderByDesc('created_at')
            ->paginate($this->getPerPage());

        return $this->paginatedResponse($posts, [
            'discipline' => $discipline,
        ]);
    }

    /**
     * Article par slug
     *
     * Retourne un article à partir de son slug.
     *
     * @group Articles
     *
     * @urlParam slug string required Slug de l’article. Exemple : titre-de-l-article
     *
     * @response 200 {
     *   "data": {
     *     "id": 1,
     *     "title": "Titre de l'article",
     *     "slug": "titre-de-l-article",
     *     "excerpt": "Résumé de l'article...",
     *     "content": "<p>Contenu HTML de l'article</p>",
     *     "discipline": "blackball",
     *     "discipline_id": 1,
     *     "year": 2026,
     *     "favoris": false,
     *     "image": null,
     *     "image_url": null,
     *     "video": null,
     *     "created_at": "2026-02-02T08:11:13.000000Z",
     *     "updated_at": "2026-02-02T08:31:49.000000Z"
     *   },
     *   "meta": [],
     *   "links": [],
     *   "error": null
     * }
     */
    public function getPostBySlug(string $slug): JsonResponse
    {
        $post = Post::query()
            ->published()
            ->where('slug', $slug)
            ->first();

        if (! $post) {
            return response()->json([
                'data' => null,
                'meta' => [],
                'links' => [],
                'error' => [
                    'code' => 'post_not_found',
                    'message' => 'Article not found',
                ],
            ], 404);
        }

        return $this->singleResponse($post);
    }

    /**
     * Articles favoris
     *
     * Retourne les articles marqués comme favoris.
     *
     * @group Articles
     *
     * @queryParam per_page integer Nombre d’articles par page. Min: 1. Max: 100. Default: 10. Exemple : 10
     *
     * @response 200 {
     *   "data": [],
     *   "meta": {
     *     "favoris": true,
     *     "count": 3,
     *     "current_page": 1,
     *     "last_page": 1,
     *     "per_page": 10,
     *     "from": 1,
     *     "to": 3,
     *     "total": 3
     *   },
     *   "links": [],
     *   "error": null
     * }
     */
    public function getPostIsFavoris(): JsonResponse
    {
        $posts = Post::query()
            ->published()
            ->where('favoris', true)
            ->orderByDesc('created_at')
            ->paginate($this->getPerPage());

        return $this->paginatedResponse($posts, [
            'favoris' => true,
        ]);
    }

    /**
     * Articles par décennie
     *
     * Retourne les articles appartenant à la décennie calculée depuis l’année fournie.
     *
     * Exemple : 2023 retourne les articles de 2020 à 2029.
     *
     * @group Articles
     *
     * @urlParam year integer required Année utilisée pour calculer la décennie. Exemple : 2023
     *
     * @queryParam per_page integer Nombre d’articles par page. Min: 1. Max: 100. Default: 10. Exemple : 10
     *
     * @response 200 {
     *   "data": [],
     *   "meta": {
     *     "decade": "2020-2029",
     *     "start_year": 2020,
     *     "end_year": 2029,
     *     "count": 8,
     *     "current_page": 1,
     *     "last_page": 1,
     *     "per_page": 10,
     *     "from": 1,
     *     "to": 8,
     *     "total": 8
     *   },
     *   "links": [],
     *   "error": null
     * }
     */
    public function getPostByDecade(int $year): JsonResponse
    {
        $startDecade = (int) floor($year / 10) * 10;
        $endDecade = $startDecade + 9;

        $posts = Post::query()
            ->published()
            ->whereBetween('year', [$startDecade, $endDecade])
            ->orderBy('year')
            ->orderByDesc('created_at')
            ->paginate($this->getPerPage());

        return $this->paginatedResponse($posts, [
            'decade' => "{$startDecade}-{$endDecade}",
            'start_year' => $startDecade,
            'end_year' => $endDecade,
        ]);
    }

    public function getPostByPeriod(string $period): JsonResponse
    {
        $currentDecade = (int) floor(now()->year / 10) * 10;

        $previousDecadeStart = $currentDecade - 10;
        $previousDecadeEnd = $currentDecade - 1;

        $secondPreviousDecadeStart = $currentDecade - 20;
        $secondPreviousDecadeEnd = $currentDecade - 11;

        $beforeYear = $currentDecade - 20;

        $periods = [
            "depuis_{$currentDecade}" => [
                'label' => "Depuis {$currentDecade}",
                'start_year' => $currentDecade,
                'end_year' => null,
                'operator' => '>=',
            ],
            "{$previousDecadeStart}_{$previousDecadeEnd}" => [
                'label' => "{$previousDecadeStart} - {$previousDecadeEnd}",
                'start_year' => $previousDecadeStart,
                'end_year' => $previousDecadeEnd,
                'operator' => 'between',
            ],
            "{$secondPreviousDecadeStart}_{$secondPreviousDecadeEnd}" => [
                'label' => "{$secondPreviousDecadeStart} - {$secondPreviousDecadeEnd}",
                'start_year' => $secondPreviousDecadeStart,
                'end_year' => $secondPreviousDecadeEnd,
                'operator' => 'between',
            ],
            "avant_{$beforeYear}" => [
                'label' => "Avant {$beforeYear}",
                'start_year' => null,
                'end_year' => $beforeYear,
                'operator' => '<',
            ],
        ];

        if (! array_key_exists($period, $periods)) {
            return response()->json([
                'data' => null,
                'meta' => [],
                'links' => [],
                'error' => [
                    'code' => 'invalid_period',
                    'message' => 'Invalid period. Valid values: '.implode(', ', array_keys($periods)),
                ],
            ], 400);
        }

        $selectedPeriod = $periods[$period];

        // Archives de la page club : uniquement les articles du club (hors disciplines).
        $posts = Post::query()
            ->published()
            ->club()
            ->when($selectedPeriod['operator'] === '>=', function ($query) use ($selectedPeriod) {
                $query->where('year', '>=', $selectedPeriod['start_year']);
            })
            ->when($selectedPeriod['operator'] === '<', function ($query) use ($selectedPeriod) {
                $query->where('year', '<', $selectedPeriod['end_year']);
            })
            ->when($selectedPeriod['operator'] === 'between', function ($query) use ($selectedPeriod) {
                $query->whereBetween('year', [
                    $selectedPeriod['start_year'],
                    $selectedPeriod['end_year'],
                ]);
            })
            ->orderByDesc('year')
            ->orderByDesc('created_at')
            ->paginate($this->getPerPage());

        return $this->paginatedResponse($posts, [
            'period' => $period,
            'label' => $selectedPeriod['label'],
            'start_year' => $selectedPeriod['start_year'],
            'end_year' => $selectedPeriod['end_year'],
        ]);
    }

    /**
     * Articles par année
     *
     * Retourne les articles associés à une année donnée.
     *
     * @group Articles
     *
     * @urlParam year integer required Année des articles. Exemple : 2026
     *
     * @queryParam per_page integer Nombre d’articles par page. Min: 1. Max: 100. Default: 10. Exemple : 10
     *
     * @response 200 {
     *   "data": [],
     *   "meta": {
     *     "year": 2026,
     *     "count": 5,
     *     "current_page": 1,
     *     "last_page": 1,
     *     "per_page": 10,
     *     "from": 1,
     *     "to": 5,
     *     "total": 5
     *   },
     *   "links": [],
     *   "error": null
     * }
     */
    public function getPostByYear(int $year): JsonResponse
    {
        $posts = Post::query()
            ->published()
            ->where('year', $year)
            ->orderBy('year')
            ->orderByDesc('created_at')
            ->paginate($this->getPerPage());

        return $this->paginatedResponse($posts, [
            'year' => $year,
        ]);
    }

    /**
     * Détermine le nombre d’éléments par page.
     *
     * Règles :
     * - défaut : 10
     * - minimum : 1
     * - maximum : 100
     */
    private function getPerPage(): int
    {
        $perPage = (int) request('per_page', 10);

        if ($perPage < 1) {
            return 10;
        }

        return min($perPage, 100);
    }

    /**
     * Retourne une réponse JSON pour un article unique.
     */
    private function singleResponse(Post $post, array $meta = []): JsonResponse
    {
        return response()->json([
            'data' => new PostResource($post),
            'meta' => $meta,
            'links' => [],
            'error' => null,
        ]);
    }

    /**
     * Retourne une réponse JSON paginée.
     *
     * Inclut :
     * - données transformées via PostResource
     * - pagination complète
     */
    private function paginatedResponse(LengthAwarePaginator $posts, array $meta = []): JsonResponse
    {
        return response()->json([
            'data' => PostResource::collection($posts->items()),
            'meta' => array_merge($meta, [
                'count' => $posts->total(),
                'current_page' => $posts->currentPage(),
                'last_page' => $posts->lastPage(),
                'per_page' => $posts->perPage(),
                'from' => $posts->firstItem(),
                'to' => $posts->lastItem(),
                'total' => $posts->total(),
            ]),
            'links' => [
                'first' => $posts->url(1),
                'last' => $posts->url($posts->lastPage()),
                'prev' => $posts->previousPageUrl(),
                'next' => $posts->nextPageUrl(),
            ],
            'error' => null,
        ]);
    }
}
