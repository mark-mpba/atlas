<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Routing\Controller;
use Modules\Admin\Services\DashboardService;

/**
 * Class DashboardController
 */
class DashboardController extends Controller
{
    /**
     * DashboardController constructor.
     *
     * @param DashboardService $dashboardService
     */
    public function __construct(
        protected DashboardService $dashboardService
    ) {
    }

    /**
     * Display the admin dashboard.
     *
     * @return View
     */
    public function index(): View
    {
        return view('admin::dashboard.index', [
            'stats' => $this->dashboardService->getStats(),
        ]);
    }
}
