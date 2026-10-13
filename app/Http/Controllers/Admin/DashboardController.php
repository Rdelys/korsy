<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminStats;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(AdminStats $stats): View
    {
        return view('admin.dashboard', [
            'kpis' => $stats->kpis(),
            'chart' => $stats->chart(),
            'sources' => $stats->sources(),
        ]);
    }
}