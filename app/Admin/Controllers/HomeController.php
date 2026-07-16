<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Services\ClubDashboardService;
use OpenAdmin\Admin\Layout\Content;

class HomeController extends Controller
{
    public function index(Content $content, ClubDashboardService $dashboard)
    {
        return $content
            ->title('Tableau de bord')
            ->description('Vue d\'ensemble du club')
            ->row(view('admin.dashboard.index', [
                'metrics' => $dashboard->metrics(),
            ]));
    }
}
