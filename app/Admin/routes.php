<?php

use App\Admin\Controllers\AdminCalendrierSnooker;
use App\Admin\Controllers\AdminDocumentController;
use App\Admin\Controllers\AdminIndex;
use App\Admin\Controllers\AdminLicenciesController;
use App\Admin\Controllers\AdminPartenairesController;
use App\Admin\Controllers\AdminPostController;
use App\Admin\Controllers\LicenseImportBatchAdminController;
use App\Admin\Controllers\CueScorePlayerMappingController;
use App\Admin\Controllers\CueScoreRankingController;
use App\Admin\Controllers\americain\AdminAmericainCalendrier;
use App\Admin\Controllers\americain\AdminAmericainCalendrierDepartemental;
use App\Admin\Controllers\americain\AdminAmericainCalendrierInternational;
use App\Admin\Controllers\americain\AdminAmericainCalendrierNational;
use App\Admin\Controllers\americain\AdminAmericainCalendrierRegional;
use App\Admin\Controllers\blackball\AdminCalendrierDepartemental;
use App\Admin\Controllers\blackball\AdminCalendrierInternational;
use App\Admin\Controllers\blackball\AdminCalendrierNational;
use App\Admin\Controllers\blackball\AdminCalendrierRegional;
use App\Admin\Controllers\carambole\AdminCaramboleCalendrier;
use App\Admin\Controllers\carambole\AdminCaramboleCalendrierDepartemental;
use App\Admin\Controllers\carambole\AdminCaramboleCalendrierInternational;
use App\Admin\Controllers\carambole\AdminCaramboleCalendrierNational;
use App\Admin\Controllers\carambole\AdminCaramboleCalendrierRegional;
use App\Admin\Controllers\carambole\AdminCaramboleClassement;
use App\Admin\Controllers\ContactAdminController;
use App\Admin\Controllers\MenuController;
use App\Admin\Controllers\SiteSettingController;
use App\Admin\Controllers\SiteSettingDashboardController;
use App\Admin\Controllers\snooker\AdminSnookerCalendrier;
use App\Admin\Controllers\snooker\AdminSnookerCalendrierDepartemental;
use App\Admin\Controllers\snooker\AdminSnookerCalendrierInternational;
use App\Admin\Controllers\snooker\AdminSnookerCalendrierNational;
use App\Admin\Controllers\snooker\AdminSnookerCalendrierRegional;
use App\Admin\Controllers\CalendarEventController;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Artisan;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
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

    // Route pour le blackball admin
    $router->resource('calendrier_departementals', AdminCalendrierDepartemental::class);
    $router->resource('calendrier_nationals', AdminCalendrierNational::class);
    $router->resource('calendrier_regionals', AdminCalendrierRegional::class);
    $router->resource('calendrier_internationals', AdminCalendrierInternational::class);
    $router->resource('documents', AdminDocumentController::class);

    // Route pour le carambole admin
    $router->resource('classement-caramboles', AdminCaramboleClassement::class);
    $router->resource('carambole-calendriers', AdminCaramboleCalendrier::class);
    $router->resource('carambole-calendrier-internationals', AdminCaramboleCalendrierInternational::class)->parameters(['carambole-calendrier-internationals' => 'caramboleCalIntl']);
    $router->resource('carambole-calendrier-nationals', AdminCaramboleCalendrierNational::class)->parameters(['carambole-calendrier-nationals' => 'caramboleCalNat']);
    $router->resource('carambole-calendrier-regionals', AdminCaramboleCalendrierRegional::class)->parameters(['carambole-calendrier-regionals' => 'caramboleCalReg']);
    $router->resource('carambole-calendrier-departementals', AdminCaramboleCalendrierDepartemental::class)->parameters(['carambole-calendrier-departementals' => 'caramboleCalDep']);


    $router->resource('indices', AdminIndex::class);
    $router->resource('contacts', ContactAdminController::class);
    $router->resource('site-settings', SiteSettingController::class);
    $router->resource('licencies', AdminLicenciesController::class);
    $router->resource('partenaires', AdminPartenairesController::class);

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
    $router->resource('snooker-calendrier-internationals', AdminSnookerCalendrierInternational::class)->parameters(['snooker-calendrier-internationals' => 'snookerCalIntl']);
    $router->resource('snooker-calendrier-nationals', AdminSnookerCalendrierNational::class)->parameters(['snooker-calendrier-nationals' => 'snookerCalNat']);
    $router->resource('snooker-calendrier-regionals', AdminSnookerCalendrierRegional::class)->parameters(['snooker-calendrier-regionals' => 'snookerCalReg']);
    $router->resource('snooker-calendrier-departementals', AdminSnookerCalendrierDepartemental::class)->parameters(['snooker-calendrier-departementals' => 'snookerCalDep']);

    // Route pour l'américain admin calendrier
    $router->resource('americain-calendriers', AdminAmericainCalendrier::class);
    $router->resource('americain-calendrier-internationals', AdminAmericainCalendrierInternational::class)->parameters(['americain-calendrier-internationals' => 'americainCalIntl']);
    $router->resource('americain-calendrier-nationals', AdminAmericainCalendrierNational::class)->parameters(['americain-calendrier-nationals' => 'americainCalNat']);
    $router->resource('americain-calendrier-regionals', AdminAmericainCalendrierRegional::class)->parameters(['americain-calendrier-regionals' => 'americainCalReg']);
    $router->resource('americain-calendrier-departementals', AdminAmericainCalendrierDepartemental::class)->parameters(['americain-calendrier-departementals' => 'americainCalDep']);

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
            $router->resource(
                "calendriers/{$discipline}/{$scope}/events",
                CalendarEventController::class
            );
        }
    }

    $router->get('calendrier_internationals', [AdminCalendrierInternational::class, 'index']);
    $router->get('calendrier_nationals', [AdminCalendrierNational::class, 'index']);
    $router->get('calendrier_regionals', [AdminCalendrierRegional::class, 'index']);
    $router->get('documents', [AdminDocumentController::class, 'index']);
    $router->get('carambole-calendriers', [AdminCaramboleCalendrier::class, 'index']);

    $router->post('/menu/{id}/toggle', function ($id) {
        $menu = \App\Models\Menu::findOrFail($id);
        $menu->actif = !$menu->actif;
        $menu->save();

        return response()->json(['success' => true, 'actif' => $menu->actif]);
    });
    $router->post('/menu/{$id}/toggle', [MenuController::class, 'toggle']);
});
