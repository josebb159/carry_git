<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Domains\Dashboard\Services\DashboardService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        protected DashboardService $dashboardService
    ) {
    }

    public function index(): View
    {
        $stats = $this->dashboardService->getStats();

        return view('dashboard.index', compact('stats'));
    }
}
