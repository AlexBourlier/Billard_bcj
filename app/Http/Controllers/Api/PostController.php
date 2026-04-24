<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Support\DisciplineMapper;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;

class PostController extends Controller
{
    public function index(): JsonResponse
    {
        $posts = Post::query()
            ->orderByDesc('created_at')
            ->paginate($this->getPerPage());

        return $this->paginatedResponse($posts);
    }

    public function show(int $id): JsonResponse
    {
        $post = Post::query()->findOrFail($id);

        return $this->singleResponse($post);
    }

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

    public function getPostBySlug(string $slug): JsonResponse
    {
        $post = Post::query()
            ->where('slug', $slug)
            ->firstOrFail();

        return $this->singleResponse($post);
    }

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

    private function getPerPage(): int
    {
        $perPage = (int) request('per_page', 10);

        if ($perPage < 1) {
            return 10;
        }

        return min($perPage, 100);
    }

    private function singleResponse(Post $post, array $meta = []): JsonResponse
    {
        return response()->json([
            'data' => new PostResource($post),
            'meta' => $meta,
            'links' => [],
            'error' => null,
        ]);
    }

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