<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DocumentResource;
use App\Models\Document;
use App\Support\DisciplineMapper;
use Illuminate\Http\JsonResponse;

/**
 * Contrôleur API pour la gestion des documents publics.
 *
 * Permet de :
 * - lister tous les documents
 * - filtrer les documents par discipline
 * - récupérer un document spécifique
 *
 * Les réponses respectent le format standard :
 * data / meta / links / error
 */
class DocumentController extends Controller
{
    /**
     * Retourne la liste de tous les documents.
     *
     * @return JsonResponse
     */
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

    /**
     * Retourne les documents pour une discipline donnée.
     *
     * @param string $discipline Slug de la discipline
     *
     * @return JsonResponse
     */
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

    /**
     * Retourne un document spécifique pour une discipline.
     *
     * Si le document n'existe pas, retourne une erreur 404.
     *
     * @param string $discipline Slug de la discipline
     * @param int $id Identifiant du document
     *
     * @return JsonResponse
     */
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

    /**
     * Retourne une réponse JSON standardisée pour une discipline invalide.
     *
     * @return JsonResponse
     */
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