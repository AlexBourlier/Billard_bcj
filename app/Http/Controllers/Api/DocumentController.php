<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DocumentResource;
use App\Models\Document;
use App\Support\DisciplineMapper;
use Illuminate\Http\JsonResponse;

class DocumentController extends Controller
{
    public function index(): JsonResponse
    {
        $documents = Document::query()->get();

        return response()->json([
            'data' => DocumentResource::collection($documents),
            'meta' => [
                'count' => $documents->count(),
            ],
            'links' => [],
            'error' => null,
        ]);
    }

    public function byDiscipline(string $discipline): JsonResponse
    {
        $disciplineId = DisciplineMapper::idFromSlug($discipline);

        if ($disciplineId === null) {
            return $this->disciplineNotFoundResponse();
        }

        $documents = Document::query()
            ->where('discipline', $disciplineId)
            ->get();

        return response()->json([
            'data' => DocumentResource::collection($documents),
            'meta' => [
                'discipline' => $discipline,
                'count' => $documents->count(),
            ],
            'links' => [],
            'error' => null,
        ]);
    }

    public function show(string $discipline, int $id): JsonResponse
    {
        $disciplineId = DisciplineMapper::idFromSlug($discipline);

        if ($disciplineId === null) {
            return $this->disciplineNotFoundResponse();
        }

        $document = Document::query()
            ->where('discipline', $disciplineId)
            ->where('id', $id)
            ->first();

        if (!$document) {
            return response()->json([
                'data' => null,
                'meta' => [
                    'discipline' => $discipline,
                    'document_id' => $id,
                ],
                'links' => [],
                'error' => [
                    'code' => 'document_not_found',
                    'message' => 'Document not found',
                ],
            ], 404);
        }

        return response()->json([
            'data' => new DocumentResource($document),
            'meta' => [
                'discipline' => $discipline,
                'document_id' => $id,
            ],
            'links' => [],
            'error' => null,
        ]);
    }

    private function disciplineNotFoundResponse(): JsonResponse
    {
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
}