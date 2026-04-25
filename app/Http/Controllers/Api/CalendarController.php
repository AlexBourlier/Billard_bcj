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
     * Retourne la liste de tous les calendriers actifs.
     *
     * @return JsonResponse
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
     * Retourne les calendriers actifs pour une discipline donnée.
     *
     * @param string $discipline Slug de la discipline
     *
     * @return JsonResponse
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
     * Retourne un calendrier spécifique (discipline + scope) avec ses événements.
     *
     * Inclut :
     * - les informations du calendrier
     * - la liste des événements associés
     * - les liens liés aux événements
     *
     * @param string $discipline Slug de la discipline
     * @param string $scope Scope du calendrier (ex: national, régional)
     *
     * @return JsonResponse
     */
    public function byDisciplineAndScope(string $discipline, string $scope): JsonResponse
    {
        $this->abortIfInvalidDiscipline($discipline);
        $this->abortIfInvalidScope($scope);

        $calendar = Calendar::query()
            ->active()
            ->byDiscipline($discipline)
            ->byScope($scope)
            ->with(['events.links'])
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