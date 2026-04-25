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
     * Retourne la liste paginée des articles.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $posts = Post::query()
            ->orderByDesc('created_at')
            ->paginate($this->getPerPage());

        return $this->paginatedResponse($posts);
    }

    /**
     * Retourne un article par son identifiant.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $post = Post::query()->findOrFail($id);

        return $this->singleResponse($post);
    }

    /**
     * Retourne les articles d'une discipline donnée.
     *
     * @param string $discipline Slug de la discipline
     * @return JsonResponse
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
            ->where('discipline', $disciplineId)
            ->orderByDesc('created_at')
            ->paginate($this->getPerPage());

        return $this->paginatedResponse($posts, [
            'discipline' => $discipline,
        ]);
    }

    /**
     * Retourne un article via son slug.
     *
     * @param string $slug
     * @return JsonResponse
     */
    public function getPostBySlug(string $slug): JsonResponse
    {
        $post = Post::query()
            ->where('slug', $slug)
            ->firstOrFail();

        return $this->singleResponse($post);
    }

    /**
     * Retourne les articles marqués comme favoris.
     *
     * @return JsonResponse
     */
    public function getPostIsFavoris(): JsonResponse
    {
        $posts = Post::query()
            ->where('favoris', true)
            ->orderByDesc('created_at')
            ->paginate($this->getPerPage());

        return $this->paginatedResponse($posts, [
            'favoris' => true,
        ]);
    }

    /**
     * Retourne les articles d'une décennie donnée.
     *
     * Exemple :
     * - 2023 → décennie 2020-2029
     *
     * @param int $year
     * @return JsonResponse
     */
    public function getPostByDecade(int $year): JsonResponse
    {
        $startDecade = (int) floor($year / 10) * 10;
        $endDecade = $startDecade + 9;

        $posts = Post::query()
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

    /**
     * Retourne les articles d'une année donnée.
     *
     * @param int $year
     * @return JsonResponse
     */
    public function getPostByYear(int $year): JsonResponse
    {
        $posts = Post::query()
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
     *
     * @return int
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
     *
     * @param Post $post
     * @param array $meta
     * @return JsonResponse
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
     *
     * @param LengthAwarePaginator $posts
     * @param array $meta
     * @return JsonResponse
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