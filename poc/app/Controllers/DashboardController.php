<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class DashboardController extends BaseController
{
    /**
     * Displays the dashboard
     *
     * @return string Render events in sight.
     */
    public function index()
    {
        return view('dashboard/index');
    }
}
