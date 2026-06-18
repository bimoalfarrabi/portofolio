<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PortfolioLog;
use App\Models\PortfolioProject;
use App\Models\PortfolioSkill;
use App\Models\PortfolioStat;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'projectCount' => PortfolioProject::count(),
            'skillCount' => PortfolioSkill::count(),
            'logCount' => PortfolioLog::count(),
            'statCount' => PortfolioStat::count(),
            'recentProjects' => PortfolioProject::latest()->take(4)->get(),
            'recentLogs' => PortfolioLog::latest()->take(4)->get(),
        ]);
    }
}
