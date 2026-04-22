<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
            'count' => $calendars->count(),
            'data' => $calendars,
        ]);
    }

    public function byDiscipline(string $discipline): JsonResponse
    {
        $this->abortIfInvalidDiscipline($discipline);

        $calendars = Calendar::query()
            ->active()
            ->byDiscipline($discipline)
            ->ordered()
            ->with(['events.links'])
            ->get();

        return response()->json([
            'discipline' => $discipline,
            'count' => $calendars->count(),
            'data' => $calendars,
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
            'calendar' => $calendar->only([
                'id',
                'discipline',
                'scope',
                'name',
                'slug',
                'source_type',
                'is_active',
                'display_name',
                'created_at',
                'updated_at',
            ]),
            'count' => $calendar->events->count(),
            'data' => $calendar->events,
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