<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RedirectResponse;

class HomeController extends BaseController
{
    /**
     * Redirect to login page
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function index(): RedirectResponse
    {
        return redirect()->to('/login');
    }
}
