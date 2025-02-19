<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Services\UserService;

class AuthController extends BaseController
{

    protected $userService;

    public function __construct()
    {
        $this->userService = new UserService();
    }

    public function login()
    {
        // Check if the user is already connected
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }

        helper(['form']);
        return view('/auth/login');
    }

    public function loginSubmit()
    {
        try {
            helper(['form']);
            // If the validation is OK, check in the database
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');

            if ($this->userService->login($email, $password)) {
                return redirect()->to('/dashboard');
            }

            return view('auth/login', [
                'error' => 'Email or incorrect password.',
            ]);
        } catch (\Exception $e) {
            return view('auth/login', [
                'error' => 'An error occurred when login : ' . $e->getMessage(),
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
