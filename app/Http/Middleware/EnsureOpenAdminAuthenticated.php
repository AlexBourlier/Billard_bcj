<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use OpenAdmin\Admin\Facades\Admin;

class EnsureOpenAdminAuthenticated
{
    public function handle(Request $request, Closure $next)
    {
        if (! Admin::user()) {
            return response()->json([
                'message' => 'Unauthenticated.'
            ], 401);
        }

        return $next($request);
    }
}