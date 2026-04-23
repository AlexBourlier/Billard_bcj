<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
        $disciplineId = $this->getDisciplineId($discipline);

        if ($disciplineId === null) {
            return response()->json([
                'message' => 'Discipline not found',
                'error' => 'discipline_not_found',
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

        return $this->paginatedResponse($posts);
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

    private function singleResponse(Post $post, array $extra = []): JsonResponse
    {
        return response()->json(array_merge($extra, [
            'data' => new PostResource($post),
            'error' => null,
        ]));
    }

    private function getDisciplineId(string $discipline): ?int
    {
        $mapping = [
            'blackball' => 1,
            'carambole' => 2,
            'snooker' => 3,
            'americain' => 4,
        ];

        return $mapping[$discipline] ?? null;
    }

    private function paginatedResponse(LengthAwarePaginator $posts, array $extra = []): JsonResponse
    {
        return response()->json(array_merge($extra, [
            'count' => $posts->total(),
            'data' => PostResource::collection($posts->items()),
            'meta' => [
                'current_page' => $posts->currentPage(),
                'last_page' => $posts->lastPage(),
                'per_page' => $posts->perPage(),
                'from' => $posts->firstItem(),
                'to' => $posts->lastItem(),
                'total' => $posts->total(),
            ],
            'links' => [
                'first' => $posts->url(1),
                'last' => $posts->url($posts->lastPage()),
                'prev' => $posts->previousPageUrl(),
                'next' => $posts->nextPageUrl(),
            ],
            'error' => null,
        ]));
    }
}