<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Domains\Dashboard\Services\DashboardService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(protected
        DashboardService $dashboardService
        )
    {
    }

    public function index(): View
    {
        $user = auth()->user();

        // Prevent delivery folk from accessing the admin/client dashboard
        if ($user->hasRole('delivery')) {
            abort(403, 'Acceso no autorizado al panel web.');
        }

        $stats = $this->dashboardService->getStats($user);

        return view('dashboard.index', compact('stats'));
    }
}
