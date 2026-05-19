<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\ClubController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\SnookerController;
use App\Http\Controllers\CuescoreController;
use App\Http\Controllers\AmericainController;
use App\Http\Controllers\BlackballController;
use App\Http\Controllers\CaramboleController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [IndexController::class, 'index'])->name('index');

Route::get('/club', [ClubController::class, 'index'])->name('club');
Route::get('/club/{post}', [ClubController::class, 'show'])->name('club.show');
Route::get('/club/annee/{decade}', [ClubController::class, 'clubByDecade'])->name('club.byDecade');


Route::get('/blackball/calendrier', [BlackballController::class, 'calendrier'])->name('blackball.calendrier');
Route::get('/blackball/document', function () {
    return view('blackball.document');
})->name('blackball.document');
Route::get('/blackball/classement', [BlackballController::class, 'classement'])->name('blackball.classement');

Route::get('/blackball', [BlackballController::class, 'index'])->name('blackball');
Route::get('/blackball/{post}', [BlackballController::class, 'show'])->name('blackball.show');

Route::get('/carambole/calendrier', [CaramboleController::class, 'calendrier'])->name('carambole.calendrier');
Route::get('/carambole/document', function () {
    return view('carambole.document');
})->name('carambole.document');
// Route::get('/carambole/galerie', [CaramboleController::class, 'galerie'])->name('carambole.gallerie');
Route::get('/carambole/classement', [CaramboleController::class, 'classementMultiple'])->name('carambole.classement');
Route::get('/carambole/classement/pdf', [CaramboleController::class, 'classementPdf'])->name('carambole.classement-pdf');

Route::get('/carambole', [CaramboleController::class, 'index'])->name('carambole');
Route::get('/carambole/{post}', [CaramboleController::class, 'show'])->name('carambole.show');


Route::get('/snooker/calendrier', [SnookerController::class, 'calendrier'])->name('snooker.calendrier');
Route::get('/snooker/document', function () {
    return view('snooker.document');
})->name('snooker.document');
Route::get('/snooker/classement', [SnookerController::class, 'classement'])->name('snooker.classement');
Route::get('/snooker', [SnookerController::class, 'index'])->name('snooker');
Route::get('/snooker/{post}', [SnookerController::class, 'show'])->name('snooker.show');

Route::get('/americain/calendrier', [AmericainController::class, 'calendrier'])->name('americain.calendrier');
Route::get('/americain', [AmericainController::class, 'index'])->name('americain');
Route::get('/americain/document', function () {
    return view('americain.document');
})->name('americain.document');
Route::get('/americain/classement', [AmericainController::class, 'classement'])->name('americain.classement');
Route::get('/americain/{post}', [AmericainController::class, 'show'])->name('americain.show');

Route::get('/cuescore/{id}', [CuescoreController::class, 'show'])->name('cuescore.show');

Route::get('contact', [ContactController::class, 'index'])->name('contact');
Route::post('contact', [ContactController::class, 'send'])
    ->middleware('throttle:5,1')
    ->name('contact.send');

// Page CGU / Mentions légale / RGPD
Route::get('mentions-legales', function () {
    return view ('mentions-legales');
});
Route::get('conditions-generales-utilisation', function () {
    return view ('cgu');
});
Route::get('politique-confidentialite', function () {
    return view ('politique-confidentialite');
});

Route::get('/cuescore', function() {
    return view('cuescore');
});

// Route de test pour la langue
Route::get('test-lang', function () {
    return __('validation.mimes', ['attribute' => 'fichier', 'values' => 'pdf']);
});

// Route test
Route::get('/test', function () {
    return view('test');
});

// Route test
Route::get('/calendrier', function () {
    return view('calendrier');
})->name('calendrier');

Route::get('/files/{path}', function (string $path) {
    abort_unless(Storage::disk('public')->exists($path), 404);
    return response()->file(Storage::disk('public')->path($path), [
        'Cache-Control' => 'public, max-age=86400',
    ]);
})->where('path', '.*')->name('files.public');


if (app()->environment(['local', 'staging'])) {
    Route::prefix('_errors')->group(function () {
        $codes = [401, 402, 403, 404, 419, 429, 500, 503];

        Route::get('/', function () use ($codes) {
            // petite page index de liens (optionnelle)
            return response()->view('errors.preview-index', compact('codes'));
        });

        Route::get('/{code}', function ($code) use ($codes) {
            abort_unless(in_array((int)$code, $codes, true), 404);
            // Rend exactement resources/views/errors/{code}.blade.php
            return response()->view("errors.$code", [], (int)$code);
        })->whereNumber('code');
    });
}