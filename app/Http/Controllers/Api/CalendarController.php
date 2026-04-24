<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CalendarEventResource;
use App\Http\Resources\CalendarResource;
use App\Models\Calendar;
use Illuminate\Http\JsonResponse;

class CalendarController extends Controller
{
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

    private function abortIfInvalidDiscipline(string $discipline): void
    {
        abort_unless(
            Calendar::isValidDiscipline($discipline),
            404,
            'Discipline invalide.'
        );
    }

    private function abortIfInvalidScope(string $scope): void
    {
        abort_unless(
            Calendar::isValidScope($scope),
            404,
            'Scope invalide.'
        );
    }
}