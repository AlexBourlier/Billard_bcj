<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use OpenAdmin\Admin\Admin;
use OpenAdmin\Admin\Controllers\Dashboard;
use OpenAdmin\Admin\Layout\Column;
use OpenAdmin\Admin\Layout\Content;
use OpenAdmin\Admin\Layout\Row;

class AdminAmericainController extends Controller
{
    public function index(Content $content)
    {
        return $content
            ->title('Tableau de bord')
            ->description('Bienvenue dans l\'administration')
            ->row(view('admin.americain.dashboard'));
    }

    public function calendrier(Content $content)
    {
        return $content
            ->title('Tableau de bord')
            ->description('Bienvenue dans l\'administration')
            ->row(view('admin.americain.calendrier'));
    }

    public function classement(Content $content)
    {
        return $content
            ->title('Tableau de bord')
            ->description('Bienvenue dans l\'administration')
            ->row(view('admin.americain.classement'));
    }

}