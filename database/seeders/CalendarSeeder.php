<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Calendar;
use App\Models\CalendarEvent;
use App\Models\CalendarEventLink;

class CalendarSeeder extends Seeder
{
    public function run(): void
    {
        $disciplines = [
            Calendar::DISCIPLINE_CARAMBOLE,
            Calendar::DISCIPLINE_SNOOKER,
            Calendar::DISCIPLINE_AMERICAIN,
            Calendar::DISCIPLINE_BLACKBALL,
        ];

        $scopes = [
            Calendar::SCOPE_INTERNATIONAL,
            Calendar::SCOPE_NATIONAL,
            Calendar::SCOPE_REGIONAL,
            Calendar::SCOPE_DEPARTEMENTAL,
        ];

        foreach ($disciplines as $discipline) {
            foreach ($scopes as $scope) {

                $calendar = Calendar::create([
                    'discipline' => $discipline,
                    'scope' => $scope,
                    'name' => ucfirst($discipline) . ' - ' . ucfirst($scope),
                    'slug' => "{$discipline}-{$scope}",
                    'source_type' => $discipline === 'carambole' ? 'manual' : 'cuescore',
                    'is_active' => true,
                ]);

                // 2 événements par calendrier
                for ($i = 1; $i <= 2; $i++) {

                    $start = now()->addDays(rand(5, 60));
                    $end = (clone $start)->addDays(rand(1, 3));

                    $event = CalendarEvent::create([
                        'calendar_id' => $calendar->id,
                        'external_id' => null,
                        'date_debut' => $start,
                        'date_fin' => $end,
                        'date_limite' => (clone $start)->subDays(rand(3, 10)),
                        'titre' => ucfirst($discipline) . " {$scope} - Open {$i}",
                        'lieu' => fake()->city(),
                        'club' => rand(0, 1) ? fake()->company() : null,
                        'url' => 'https://cuescore.com/tournament/' . rand(1000, 9999),
                        'status' => 'inscription',
                    ]);

                    // Liens par catégories
                    $categories = [
                        CalendarEventLink::CATEGORY_MIXTE,
                        CalendarEventLink::CATEGORY_FEMININ,
                        CalendarEventLink::CATEGORY_VETERAN,
                    ];

                    foreach ($categories as $index => $category) {
                        CalendarEventLink::create([
                            'calendar_event_id' => $event->id,
                            'category' => $category,
                            'label' => null,
                            'url' => "https://cuescore.com/{$category}/" . rand(1000, 9999),
                            'sort_order' => $index + 1,
                        ]);
                    }
                }
            }
        }
    }
}