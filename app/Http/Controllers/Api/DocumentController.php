<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Http\Resources\DocumentResource;
use Illuminate\Http\JsonResponse;
use App\Support\DisciplineMapper;

class DocumentController extends Controller
{
    public function index(): JsonResponse
    {
        $documents = Document::query()->get();

        return response()->json([
            'count' => $documents->count(),
            'data' => DocumentResource::collection($documents),
            'error' => null,
        ]);
    }

    public function byDiscipline(string $discipline): JsonResponse
    {
        $disciplineId = DisciplineMapper::idFromSlug($discipline);

        if ($disciplineId === null) {
            return response()->json([
                'message' => 'Discipline not found',
                'error' => 'discipline_not_found',
            ], 404);
        }

        $documents = Document::query()
            ->where('discipline', $disciplineId)
            ->get();

        return response()->json([
            'discipline' => $discipline,
            'count' => $documents->count(),
            'data' => DocumentResource::collection($documents),
            'error' => null,
        ]);
    }

    public function show(string $discipline, int $id): JsonResponse
    {
        $disciplineId = DisciplineMapper::idFromSlug($discipline);

        if ($disciplineId === null) {
            return response()->json([
                'message' => 'Discipline not found',
                'error' => 'discipline_not_found',
            ], 404);
        }

        $document = Document::query()
            ->where('discipline', $disciplineId)
            ->where('id', $id)
            ->first();

        if (!$document) {
            return response()->json([
                'message' => 'Document not found',
                'error' => 'document_not_found',
            ], 404);
        }

        return response()->json([
            'discipline' => $discipline,
            'data' => new DocumentResource($document),
            'error' => null,
        ]);
    }
}