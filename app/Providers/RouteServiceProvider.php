<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        // NB : on n'utilise PAS Route::resourceVerbs() pour franciser les URLs
        // d'administration (creer/editer...). En effet, ces verbes ne sont
        // appliques aux routes d'OpenAdmin que lorsque les routes sont mises en
        // cache (production), pas en developpement (sans cache) : les URLs
        // differaient alors entre les deux environnements (edit vs editer),
        // cassant les liens du menu construits en local. On conserve donc les
        // verbes anglais par defaut (edit/create), coherents partout.

        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}
