<?php

namespace App\Controllers;

class HomeController extends BaseController
{
    /**
     * Displays the login form
     *
     * @return string Render events in sight.
     */
    public function index()
    {
        return redirect()->to('/login');
    }
}
