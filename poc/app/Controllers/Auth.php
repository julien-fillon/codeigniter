<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Services\UserService;

class Auth extends BaseController
{

    protected $userService;

    public function __construct()
    {
        $this->userService = new UserService();
    }

    public function login()
    {
        helper(['form']);
        return view('/auth/login');
    }

    public function loginSubmit()
    {
        helper(['form']);
        // If the validation is OK, check in the database
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        if ($this->userService->login($email, $password)) {
            return redirect()->to('/dashboard');
        } else {
            // In case of connection failure
            return view('auth/login', [
                'error' => 'Email ou mot de passe incorrect.',
            ]);
        }
    }

    public function logout()
    {
        // Destroy the session
        session()->destroy();
        return redirect()->to('/login'); // Return to the connection screen
    }
}
