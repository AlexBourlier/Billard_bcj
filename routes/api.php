<?php

use App\Http\Controllers\Api\CalendarController;
use App\Http\Controllers\Api\CaramboleSyncController;
use App\Http\Controllers\Api\CueScoreController;
use App\Http\Controllers\Api\DocumentController;
use App\Http\Controllers\Api\LicenciesController;
use App\Http\Controllers\Api\LicenseImportBatchController;
use App\Http\Controllers\Api\PartnerController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\PublicController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use PharIo\Manifest\License;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::post('carambole/sync', CaramboleSyncController::class)->middleware('auth:sanctum');

// routes/api.php (temporaire pour debug)
Route::post('/carambole/sync-test', \App\Http\Controllers\Api\CaramboleSyncController::class);

Route::prefix('v1')->group(function () {

    // Route pour les informations publiques du site
    Route::prefix('/public')->group(function () {
        Route::get('/site', [PublicController::class, 'site']);
    });
    // Route pour les partenaires
    Route::get('/partenaires', [PartnerController::class, 'index']);

    Route::prefix('/posts')->group(function () {
        Route::get('/', [PostController::class, 'index']);
        Route::get('/{id}', [PostController::class, 'show']);
        Route::get('/discipline/{discipline}', [PostController::class, 'getPostsByDiscipline']);
        Route::get('/slug/{slug}', [PostController::class, 'getPostBySlug']);
        Route::get('/favoris', [PostController::class, 'getPostIsFavoris']);
        Route::get('/decade/{year}', [PostController::class, 'getPostByDecade']);
        Route::get('/year/{year}', [PostController::class, 'getPostByYear']);
    });
    
    
    Route::get('/licencies', [LicenciesController::class, 'index']);
    Route::get('/licencies/search/{name}', [LicenciesController::class, 'searchByName']);

    // Routes batches
    Route::get('/license-import/batches', [LicenseImportBatchController::class, 'index']);
    Route::get('/license-import/batches/{batch}', [LicenseImportBatchController::class, 'show']);
    Route::get('/license-import/batches/{batch}/report', [LicenseImportBatchController::class, 'report']);
    Route::get('/license-import/batches/{batch}/diff', [LicenseImportBatchController::class, 'diff']);

    // routes pour les classements CueScore
    Route::prefix('/cuescore')->group(function () {
        Route::get('/rankings', [CueScoreController::class, 'index']);
        // Routes agrégées pour les classements CueScore (ex: classement + club + teams)
        Route::get('/club', [CueScoreController::class, 'clubOverview']);
        Route::get('/rankings/{ranking}', [CueScoreController::class, 'show']);
        Route::get('/rankings/{ranking}/club', [CueScoreController::class, 'club']);
        Route::get('/rankings/{ranking}/teams', [CueScoreController::class, 'teams']);
        
        Route::get('/{discipline}/{scope}/{rankingType}', [CueScoreController::class, 'byDisciplineScopeAndType']);
    });

    // Routes pour les calendriers de tournois
    Route::prefix('/calendrier')->group(function () {
       Route::get('/', [CalendarController::class, 'index']);
       Route::get('/{discipline}', [CalendarController::class, 'byDiscipline']);
       Route::get('/{discipline}/{scope}', [CalendarController::class, 'byDisciplineAndScope']); 
    });

    // Routes pour les documents liés aux différentes disciplines
    Route::prefix('/documents')->group(function () {
        Route::get('/', [DocumentController::class, 'index']);
        Route::get('/{discipline}', [DocumentController::class, 'byDiscipline']);
        Route::get('/{discipline}/{id}', [DocumentController::class, 'show']);
    });
    
});
