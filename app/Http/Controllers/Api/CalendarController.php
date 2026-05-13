<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CalendarEventResource;
use App\Http\Resources\CalendarResource;
use App\Models\Calendar;
use Illuminate\Http\JsonResponse;

/**
 * Contrôleur API pour la gestion des calendriers.
 * 
 * @group Calendars
 *
 * Expose les endpoints publics permettant de :
 * - récupérer la liste des calendriers actifs
 * - filtrer par discipline
 * - récupérer un calendrier détaillé avec ses événements
 *
 * Les réponses respectent le format standard :
 * data / meta / links / error
 */
class CalendarController extends Controller
{
    /**
     * Liste des calendriers
     *
     * Retourne la liste de tous les calendriers actifs.
     *
     * @group Calendriers
     *
     * @response 200 {
     *   "data": [
     *     {
     *       "id": 1,
     *       "discipline": "blackball",
     *       "scope": "national",
     *       "name": "Calendrier national Blackball",
     *       "slug": "blackball-national",
     *       "source_type": "ffb",
     *       "is_active": true,
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
        $calendars = Calendar::query()
            ->active()
            ->ordered()
            ->get();

        return response()->json([
            'data' => CalendarResource::collection($calendars),
            'meta' => [
                'count' => $calendars->count(),
            ],
            'links' => [],
            'error' => null,
        ]);
    }

    /**
     * Calendriers par discipline
     *
     * Retourne les calendriers actifs associés à une discipline donnée.
     *
     * @group Calendriers
     *
     * @urlParam discipline string required Slug de la discipline. Exemple : blackball
     *
     * @response 200 {
     *   "data": [
     *     {
     *       "id": 1,
     *       "discipline": "blackball",
     *       "scope": "national",
     *       "name": "Calendrier national Blackball",
     *       "slug": "blackball-national",
     *       "source_type": "ffb",
     *       "is_active": true,
     *       "events_count": 12,
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
     *   "message": "Discipline invalide."
     * }
     */
    public function byDiscipline(string $discipline): JsonResponse
    {
        $this->abortIfInvalidDiscipline($discipline);

        $calendars = Calendar::query()
            ->active()
            ->byDiscipline($discipline)
            ->ordered()
            ->withCount('events')
            ->get();

        return response()->json([
            'data' => CalendarResource::collection($calendars),
            'meta' => [
                'discipline' => $discipline,
                'count' => $calendars->count(),
            ],
            'links' => [],
            'error' => null,
        ]);
    }

    /**
     * Calendrier par discipline et scope
     *
     * Retourne un calendrier spécifique avec la liste de ses événements et leurs liens.
     *
     * @group Calendriers
     *
     * @urlParam discipline string required Slug de la discipline. Exemple : blackball
     * @urlParam scope string required Scope du calendrier. Exemple : national
     *
     * @response 200 {
     *   "data": {
     *     "calendar": {
     *       "id": 1,
     *       "discipline": "blackball",
     *       "scope": "national",
     *       "name": "Calendrier national Blackball",
     *       "slug": "blackball-national",
     *       "source_type": "ffb",
     *       "is_active": true,
     *       "created_at": "2025-01-01T10:00:00.000000Z",
     *       "updated_at": "2025-01-02T10:00:00.000000Z"
     *     },
     *     "events": [
     *       {
     *         "id": 1,
     *         "calendar_id": 1,
     *         "external_id": "event-123",
     *         "date_debut": "2026-01-10T09:00:00.000000Z",
     *         "date_fin": "2026-01-10T18:00:00.000000Z",
     *         "date_limite": "2026-01-05T23:59:00.000000Z",
     *         "titre": "Tournoi national",
     *         "lieu": "Joué-lès-Tours",
     *         "club": "BCJ37",
     *         "url": "https://example.com/event",
     *         "status": "published",
     *         "links": [
     *           {
     *             "id": 1,
     *             "category": "inscription",
     *             "label": "S'inscrire",
     *             "url": "https://example.com/register",
     *             "sort_order": 1
     *           }
     *         ]
     *       }
     *     ]
     *   },
     *   "meta": {
     *     "discipline": "blackball",
     *     "scope": "national",
     *     "count": 1
     *   },
     *   "links": [],
     *   "error": null
     * }
     *
     * @response 404 {
     *   "message": "Discipline invalide."
     * }
     */
    public function byDisciplineAndScope(string $discipline, string $scope): JsonResponse
    {
        $this->abortIfInvalidDiscipline($discipline);
        $this->abortIfInvalidScope($scope);

        $calendar = Calendar::query()
            ->active()
            ->byDiscipline($discipline)
            ->byScope($scope)
            ->with(['events.links', 'events.calendar'])
            ->firstOrFail();

        return response()->json([
            'data' => [
                'calendar' => new CalendarResource($calendar),
                'events' => CalendarEventResource::collection($calendar->events),
            ],
            'meta' => [
                'discipline' => $discipline,
                'scope' => $scope,
                'count' => $calendar->events->count(),
            ],
            'links' => [],
            'error' => null,
        ]);
    }

    /**
     * Vérifie la validité d'une discipline.
     *
     * Déclenche une erreur 404 si la discipline est invalide.
     *
     * @param string $discipline
     *
     * @return void
     */
    private function abortIfInvalidDiscipline(string $discipline): void
    {
        abort_unless(
            Calendar::isValidDiscipline($discipline),
            404,
            'Discipline invalide.'
        );
    }

    /**
     * Vérifie la validité d'un scope de calendrier.
     *
     * Déclenche une erreur 404 si le scope est invalide.
     *
     * @param string $scope
     *
     * @return void
     */
    private function abortIfInvalidScope(string $scope): void
    {
        abort_unless(
            Calendar::isValidScope($scope),
            404,
            'Scope invalide.'
        );
    }
}