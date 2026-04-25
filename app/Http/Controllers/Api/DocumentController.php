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
     * Liste des documents
     *
     * Retourne la liste de tous les documents publics.
     *
     * @group Documents
     *
     * @response 200 {
     *   "data": [
     *     {
     *       "id": 1,
     *       "title": "Règlement intérieur",
     *       "file": "documents/reglement.pdf",
     *       "file_url": "https://example.com/documents/reglement.pdf",
     *       "discipline": "blackball",
     *       "created_at": "2025-01-01T10:00:00.000000Z",
     *       "updated_at": "2025-01-02T10:00:00.000000Z"
     *     }
     *   ],
     *   "meta": {
     *     "count": 1
     *   },
     *   "links": [],
     *   "error": null
     * }
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
     * Documents par discipline
     *
     * Retourne les documents associés à une discipline donnée.
     *
     * @group Documents
     *
     * @urlParam discipline string required Slug de la discipline. Exemple : blackball
     *
     * @response 200 {
     *   "data": [
     *     {
     *       "id": 1,
     *       "title": "Règlement intérieur",
     *       "file": "documents/reglement.pdf",
     *       "file_url": "https://example.com/documents/reglement.pdf",
     *       "discipline": "blackball",
     *       "created_at": "2025-01-01T10:00:00.000000Z",
     *       "updated_at": "2025-01-02T10:00:00.000000Z"
     *     }
     *   ],
     *   "meta": {
     *     "discipline": "blackball",
     *     "count": 1
     *   },
     *   "links": [],
     *   "error": null
     * }
     *
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
     * Détail d’un document
     *
     * Retourne un document spécifique pour une discipline donnée.
     *
     * @group Documents
     *
     * @urlParam discipline string required Slug de la discipline. Exemple : blackball
     * @urlParam id integer required Identifiant du document. Exemple : 1
     *
     * @response 200 {
     *   "data": {
     *     "id": 1,
     *     "title": "Règlement intérieur",
     *     "file": "documents/reglement.pdf",
     *     "file_url": "https://example.com/documents/reglement.pdf",
     *     "discipline": "blackball",
     *     "created_at": "2025-01-01T10:00:00.000000Z",
     *     "updated_at": "2025-01-02T10:00:00.000000Z"
     *   },
     *   "meta": {
     *     "discipline": "blackball",
     *     "document_id": 1
     *   },
     *   "links": [],
     *   "error": null
     * }
     *
     * @response 404 {
     *   "data": null,
     *   "meta": {
     *     "discipline": "blackball",
     *     "document_id": 1
     *   },
     *   "links": [],
     *   "error": {
     *     "code": "document_not_found",
     *     "message": "Document not found"
     *   }
     * }
     *
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