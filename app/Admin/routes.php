<?php

use App\Admin\Controllers\AdminCalendrierSnooker;
use App\Admin\Controllers\AdminCuescoreNational;
use App\Admin\Controllers\AdminCuescoreRegional;
use App\Admin\Controllers\AdminDepartementalLink;
use App\Admin\Controllers\AdminDocumentAmericain;
use App\Admin\Controllers\AdminDocumentBlackball;
use App\Admin\Controllers\AdminDocumentCarambole;
use App\Admin\Controllers\AdminDocumentSnooker;
use App\Admin\Controllers\AdminIndex;
use App\Admin\Controllers\AdminLicenciesController;
use App\Admin\Controllers\AdminNationalLink;
use App\Admin\Controllers\AdminPartenairesController;
use App\Admin\Controllers\AdminPostController;
use App\Admin\Controllers\AdminRegionalLink;
use App\Admin\Controllers\LicenseImportBatchAdminController;
use App\Admin\Controllers\americain\AdminAmericainCalendrier;
use App\Admin\Controllers\americain\AdminAmericainCalendrierDepartemental;
use App\Admin\Controllers\americain\AdminAmericainCalendrierInternational;
use App\Admin\Controllers\americain\AdminAmericainCalendrierNational;
use App\Admin\Controllers\americain\AdminAmericainCalendrierRegional;
use App\Admin\Controllers\americain\AdminAmericainClassement;
use App\Admin\Controllers\americain\liens_cuescore\AdminAmericainDepartementalLink;
use App\Admin\Controllers\americain\liens_cuescore\AdminAmericainInternationalLink;
use App\Admin\Controllers\americain\liens_cuescore\AdminAmericainNationalLink;
use App\Admin\Controllers\americain\liens_cuescore\AdminAmericainRegionalLink;
use App\Admin\Controllers\blackball\AdminCalendrierDepartemental;
use App\Admin\Controllers\blackball\AdminCalendrierInternational;
use App\Admin\Controllers\blackball\AdminCalendrierNational;
use App\Admin\Controllers\blackball\AdminCalendrierRegional;
use App\Admin\Controllers\blackball\AdminCuescoreEquipesNationale;
use App\Admin\Controllers\blackball\AdminCuescoreEquipesRegionale;
use App\Admin\Controllers\BlackballController;
use App\Admin\Controllers\carambole\AdminCaramboleCalendrier;
use App\Admin\Controllers\carambole\AdminCaramboleCalendrierDepartemental;
use App\Admin\Controllers\carambole\AdminCaramboleCalendrierInternational;
use App\Admin\Controllers\carambole\AdminCaramboleCalendrierNational;
use App\Admin\Controllers\carambole\AdminCaramboleCalendrierRegional;
use App\Admin\Controllers\carambole\AdminCaramboleClassement;
use App\Admin\Controllers\carambole\liens_cuescore\AdminCaramboleDepartementalLink;
use App\Admin\Controllers\carambole\liens_cuescore\AdminCaramboleInternationalLink;
use App\Admin\Controllers\carambole\liens_cuescore\AdminCaramboleNationalLink;
use App\Admin\Controllers\carambole\liens_cuescore\AdminCaramboleRegionalLink;
use App\Admin\Controllers\ContactAdminController;
use App\Admin\Controllers\MenuController;
use App\Admin\Controllers\SiteSettingController;
use App\Admin\Controllers\SiteSettingDashboardController;
use App\Admin\Controllers\snooker\AdminSnookerCalendrier;
use App\Admin\Controllers\snooker\AdminSnookerCalendrierDepartemental;
use App\Admin\Controllers\snooker\AdminSnookerCalendrierInternational;
use App\Admin\Controllers\snooker\AdminSnookerCalendrierNational;
use App\Admin\Controllers\snooker\AdminSnookerCalendrierRegional;
use App\Admin\Controllers\snooker\AdminSnookerClassement;
use App\Admin\Controllers\snooker\liens_cuescore\AdminSnookerDepartementalLink;
use App\Admin\Controllers\snooker\liens_cuescore\AdminSnookerInternationalLink;
use App\Admin\Controllers\snooker\liens_cuescore\AdminSnookerNationalLink;
use App\Admin\Controllers\snooker\liens_cuescore\AdminSnookerRegionalLink;
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
    $router->get('/americain', 'AdminAmericainController@index')->name('americain.menu');
    $router->get('/americain/calendrier', 'AdminAmericainController@calendrier')->name('americain.calendrier');
    $router->get('/systeme', 'SiteSettingDashboardController@index')->name('settings.menu');
    $router->get('/blackball/classement', 'AdminBlackballController@classement')->name('blackball.classement');
    $router->get('/blackball/calendrier', 'AdminBlackballController@calendrier')->name('blackball.calendrier');
    $router->get('/club/licencies', 'AdminLicenciesController@licencies')->name('club.licencies');

    $router->get('/maj-licencies', [AdminLicenciesController::class, 'updateLicencies'])->name('maj-licencies');


    $router->resource('menus', MenuController::class);
    $router->resource('posts', AdminPostController::class);
    
    // Route pour le blackball admin
    $router->resource('calendrier_departementals', AdminCalendrierDepartemental::class);
    $router->resource('calendrier_nationals', AdminCalendrierNational::class);
    $router->resource('calendrier_regionals', AdminCalendrierRegional::class);
    $router->resource('calendrier_internationals', AdminCalendrierInternational::class);
    $router->resource('documents', AdminDocumentBlackball::class);
    $router->resource('national-links', AdminNationalLink::class);
    $router->resource('regional-links', AdminRegionalLink::class);
    $router->resource('departemental-links', AdminDepartementalLink::class);
    $router->resource('cuescore-regionals', AdminCuescoreRegional::class);
    $router->resource('cuescore-nationals', AdminCuescoreNational::class);
    $router->resource('cuescore-equipes-nationales', AdminCuescoreEquipesNationale::class);
    $router->resource('cuescore-equipes-regionales', AdminCuescoreEquipesRegionale::class);

    // Route pour le carambole admin
    $router->resource('classement-caramboles', AdminCaramboleClassement::class);
    $router->resource('documents-carambole', AdminDocumentCarambole::class);
    $router->resource('carambole-calendriers', AdminCaramboleCalendrier::class);
    $router->resource('carambole-calendrier-internationals', AdminCaramboleCalendrierInternational::class)->parameters(['carambole-calendrier-internationals' => 'caramboleCalIntl']);
    $router->resource('carambole-calendrier-nationals', AdminCaramboleCalendrierNational::class)->parameters(['carambole-calendrier-nationals' => 'caramboleCalNat']);
    $router->resource('carambole-calendrier-regionals', AdminCaramboleCalendrierRegional::class)->parameters(['carambole-calendrier-regionals' => 'caramboleCalReg']);
    $router->resource('carambole-calendrier-departementals', AdminCaramboleCalendrierDepartemental::class)->parameters(['carambole-calendrier-departementals' => 'caramboleCalDep']);
    $router->resource('carambole/liens_cuescore/national-links', AdminCaramboleNationalLink::class);
    $router->resource('carambole/liens_cuescore/regional-links', AdminCaramboleRegionalLink::class);
    $router->resource('carambole/liens_cuescore/international-links', AdminCaramboleInternationalLink::class);
    $router->resource('carambole/liens_cuescore/departemental-links', AdminCaramboleDepartementalLink::class);
    
    
    $router->resource('indices', AdminIndex::class);
    $router->resource('contacts', ContactAdminController::class);
    $router->resource('site-settings', SiteSettingController::class);
    $router->resource('licencies', AdminLicenciesController::class);
    $router->resource('partenaires', AdminPartenairesController::class);

    // Import de licences (pipeline Telemat / FFBI) - lecture seule + declenchement
    $router->get('license-import/run', [LicenseImportBatchAdminController::class, 'run'])->name('license-import.run');
    $router->get('license-import/batches', [LicenseImportBatchAdminController::class, 'index'])->name('license-import.batches.index');
    $router->get('license-import/batches/{id}', [LicenseImportBatchAdminController::class, 'show'])->name('license-import.batches.show');
    
    // Route pour le snooker admin calendrier
    $router->resource('documents-snooker', AdminDocumentSnooker::class);
    $router->resource('snooker-classements', AdminSnookerClassement::class);
    $router->resource('snooker-calendriers', AdminSnookerCalendrier::class);
    $router->resource('snooker-calendrier-internationals', AdminSnookerCalendrierInternational::class)->parameters(['snooker-calendrier-internationals' => 'snookerCalIntl']);
    $router->resource('snooker-calendrier-nationals', AdminSnookerCalendrierNational::class)->parameters(['snooker-calendrier-nationals' => 'snookerCalNat']);
    $router->resource('snooker-calendrier-regionals', AdminSnookerCalendrierRegional::class)->parameters(['snooker-calendrier-regionals' => 'snookerCalReg']);
    $router->resource('snooker-calendrier-departementals', AdminSnookerCalendrierDepartemental::class)->parameters(['snooker-calendrier-departementals' => 'snookerCalDep']);
    $router->resource('snooker/liens_cuescore/national-links', AdminSnookerNationalLink::class);
    $router->resource('snooker/liens_cuescore/regional-links', AdminSnookerRegionalLink::class);
    $router->resource('snooker/liens_cuescore/international-links', AdminSnookerInternationalLink::class);
    $router->resource('snooker/liens_cuescore/departemental-links', AdminSnookerDepartementalLink::class);
    
    // Route pour l'américain admin calendrier
    $router->resource('documents-americain', AdminDocumentAmericain::class);
    $router->resource('americain-classements', AdminAmericainClassement::class);
    $router->resource('americain-calendriers', AdminAmericainCalendrier::class);
    $router->resource('americain-calendrier-internationals', AdminAmericainCalendrierInternational::class)->parameters(['americain-calendrier-internationals' => 'americainCalIntl']);
    $router->resource('americain-calendrier-nationals', AdminAmericainCalendrierNational::class)->parameters(['americain-calendrier-nationals' => 'americainCalNat']);
    $router->resource('americain-calendrier-regionals', AdminAmericainCalendrierRegional::class)->parameters(['americain-calendrier-regionals' => 'americainCalReg']);
    $router->resource('americain-calendrier-departementals', AdminAmericainCalendrierDepartemental::class)->parameters(['americain-calendrier-departementals' => 'americainCalDep']);
    $router->resource('americain/liens_cuescore/national-links', AdminAmericainNationalLink::class);
    $router->resource('americain/liens_cuescore/regional-links', AdminAmericainRegionalLink::class);
    $router->resource('americain/liens_cuescore/international-links', AdminAmericainInternationalLink::class);
    $router->resource('americain/liens_cuescore/departemental-links', AdminAmericainDepartementalLink::class);

    $calendarRoutes = [
        'blackball',
        'carambole',
        'snooker',
        'americain',
    ];

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

    $calendarScopes = [
        'international',
        'national',
        'regional',
        'departemental',
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

    $router->get('cuescore-nationals', [AdminCuescoreNational::class, 'index']);
    $router->get('cuescore-regionals', [AdminCuescoreRegional::class, 'index']);
    $router->get('cuescore-equipes-nationales', [AdminCuescoreEquipesNationale::class, 'index']);
    $router->get('cuescore-equipes-regionales', [AdminCuescoreEquipesRegionale::class, 'index']);
    $router->get('calendrier_internationals', [AdminCalendrierInternational::class, 'index']);
    $router->get('calendrier_nationals', [AdminCalendrierNational::class, 'index']);
    $router->get('calendrier_regionals', [AdminCalendrierRegional::class, 'index']);    
    $router->get('documents', [AdminDocumentBlackball::class, 'index']);
    $router->get('carambole-calendriers', [AdminCaramboleCalendrier::class, 'index']);
    $router->get('documents-carambole', [AdminDocumentCarambole::class, 'index']);

    $router->post('/menu/{id}/toggle', function ($id) {
        $menu = \App\Models\Menu::findOrFail($id);
        $menu->actif = !$menu->actif;
        $menu->save();

        return response()->json(['success' => true, 'actif' => $menu->actif]);
    });
    $router->post('/menu/{$id}/toggle', [MenuController::class, 'toggle']);
});

