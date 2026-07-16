<?php

use App\Admin\Controllers\AdminDocumentController;
use App\Admin\Controllers\AdminIndex;
use App\Admin\Controllers\AdminLicenciesController;
use App\Admin\Controllers\AdminPartenairesController;
use App\Admin\Controllers\AdminPostController;
use App\Admin\Controllers\americain\AdminAmericainCalendrier;
use App\Admin\Controllers\CalendarEventController;
use App\Admin\Controllers\carambole\AdminCaramboleCalendrier;
use App\Admin\Controllers\carambole\AdminCaramboleClassement;
use App\Admin\Controllers\ContactAdminController;
use App\Admin\Controllers\CueScorePlayerMappingController;
use App\Admin\Controllers\CueScoreRankingController;
use App\Admin\Controllers\LicenseImportBatchAdminController;
use App\Admin\Controllers\MenuController;
use App\Admin\Controllers\SiteSettingController;
use App\Admin\Controllers\snooker\AdminSnookerCalendrier;
use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix' => config('admin.route.prefix'),
    'namespace' => config('admin.route.namespace'),
    'middleware' => config('admin.route.middleware'),
    'as' => config('admin.route.prefix').'.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');
    $router->get('/blackball', 'AdminBlackballController@index')->name('blackball.menu');
    $router->get('/club', 'AdminClubController@index')->name('club.menu');
    $router->get('/carambole', 'AdminCaramboleController@index')->name('carambole.menu');
    $router->get('/carambole/calendrier', 'AdminCaramboleController@calendrier')->name('carambole.calendrier');
    $router->get('/snooker', 'AdminSnookerController@index')->name('snooker.menu');
    $router->get('/snooker/calendrier', 'AdminSnookerController@calendrier')->name('snooker.calendrier');
    $router->get('/snooker/classement', 'AdminSnookerController@classement')->name('snooker.classement');
    $router->get('/americain', 'AdminAmericainController@index')->name('americain.menu');
    $router->get('/americain/calendrier', 'AdminAmericainController@calendrier')->name('americain.calendrier');
    $router->get('/americain/classement', 'AdminAmericainController@classement')->name('americain.classement');
    $router->get('/systeme', 'SiteSettingDashboardController@index')->name('settings.menu');
    $router->get('/blackball/classement', 'AdminBlackballController@classement')->name('blackball.classement');
    $router->get('/blackball/calendrier', 'AdminBlackballController@calendrier')->name('blackball.calendrier');
    $router->get('/club/licencies', 'AdminLicenciesController@licencies')->name('club.licencies');

    $router->resource('menus', MenuController::class);
    $router->resource('posts', AdminPostController::class);

    $router->resource('documents', AdminDocumentController::class);

    // Route pour le carambole admin
    $router->resource('classement-caramboles', AdminCaramboleClassement::class);
    $router->resource('carambole-calendriers', AdminCaramboleCalendrier::class);

    $router->resource('indices', AdminIndex::class);
    $router->resource('contacts', ContactAdminController::class);
    $router->resource('site-settings', SiteSettingController::class);
    $router->resource('licencies', AdminLicenciesController::class);
    $router->resource('partenaires', AdminPartenairesController::class);
    $router->resource('info-blocks', InfoBlockController::class);

    // Import de licences (pipeline Telemat / FFBI) - lecture seule + declenchement
    $router->get('license-import/run', [LicenseImportBatchAdminController::class, 'run'])->name('license-import.run');
    $router->get('license-import/batches', [LicenseImportBatchAdminController::class, 'index'])->name('license-import.batches.index');
    $router->get('license-import/batches/{id}', [LicenseImportBatchAdminController::class, 'show'])->name('license-import.batches.show');

    // Correspondances CueScore (revue joueur -> licencie + declenchement import)
    $router->get('cuescore-mappings/run', [CueScorePlayerMappingController::class, 'run'])->name('cuescore-mappings.run');
    $router->resource('cuescore-mappings', CueScorePlayerMappingController::class)->only(['index', 'show', 'edit', 'update', 'destroy']);

    // CRUD des classements CueScore (param-driven par ?discipline=&scope=)
    $router->resource('cuescore-classements', CueScoreRankingController::class);

    // Route pour le snooker admin calendrier
    $router->resource('snooker-calendriers', AdminSnookerCalendrier::class);

    // Route pour l'américain admin calendrier
    $router->resource('americain-calendriers', AdminAmericainCalendrier::class);

    $calendarScopes = [
        'international',
        'national',
        'regional',
        'departemental',
    ];

    $calendarDisciplines = [
        'blackball',
        'carambole',
        'snooker',
        'americain',
    ];

    foreach ($calendarDisciplines as $discipline) {
        foreach ($calendarScopes as $scope) {
            $router->get(
                "calendriers/{$discipline}/{$scope}/events/{event}/links",
                [CalendarEventController::class, 'links']
            );
            $router->post(
                "calendriers/{$discipline}/{$scope}/events/{event}/links",
                [CalendarEventController::class, 'saveLinks']
            );
            // Nom de route unique par discipline+portee : sans cela, les 16
            // combinaisons partagent le nom « events.* » (dernier segment de
            // l'URI) et la mise en cache des routes (route:cache) echoue.
            $router->resource(
                "calendriers/{$discipline}/{$scope}/events",
                CalendarEventController::class
            )->names("calendriers.{$discipline}.{$scope}.events");
        }
    }

    $router->get('documents', [AdminDocumentController::class, 'index']);
    $router->get('carambole-calendriers', [AdminCaramboleCalendrier::class, 'index']);

    $router->post('/menu/{id}/toggle', function ($id) {
        $menu = \App\Models\Menu::findOrFail($id);
        $menu->actif = ! $menu->actif;
        $menu->save();

        return response()->json(['success' => true, 'actif' => $menu->actif]);
    });
    $router->post('/menu/{$id}/toggle', [MenuController::class, 'toggle']);
});
