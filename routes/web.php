<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Le site public est désormais servi exclusivement par le frontend React
| (dossier /frontend) qui consomme l'API (routes/api.php, préfixe /api/v1).
|
| Le backend Laravel n'expose plus l'ancien site Blade. Il ne sert plus que :
|   - le back-office OpenAdmin (préfixe /admin, routes gérées par le package)
|   - l'API (routes/api.php)
|   - le service de fichiers publics ci-dessous
|
*/

// La racine du backend renvoie vers le back-office : l'ancien site public
// n'est plus servi ici.
Route::get('/', function () {
    return redirect('/' . config('admin.route.prefix', 'admin'));
});

// Service des fichiers publics (PDF de classements, documents, etc.).
Route::get('/files/{path}', function (string $path) {
    abort_unless(Storage::disk('public')->exists($path), 404);
    return response()->file(Storage::disk('public')->path($path), [
        'Cache-Control' => 'public, max-age=86400',
    ]);
})->where('path', '.*')->name('files.public');
