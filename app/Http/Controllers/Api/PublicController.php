<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MenuResource;
use App\Http\Resources\SiteSettingsResource;
use App\Models\Menu;
use App\Models\SiteSetting;
use Illuminate\Http\JsonResponse;

class PublicController extends Controller
{
    public function site(): JsonResponse
    {
        $siteSettings = SiteSetting::query()->first();
        $menus = Menu::query()
            ->where('actif', true)
            ->orderBy('nom')
            ->get();

        return response()->json([
            'data' => [
                'site_settings' => $siteSettings ? new SiteSettingsResource($siteSettings) : null,
                'menus' => MenuResource::collection($menus),
            ],
            'error' => null,
        ]);
    }
}