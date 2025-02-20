<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Services\UserService;
use App\Validators\AuthValidator;

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

            $message = ['error' => 'Email or incorrect password.'];
            // Validation via the external validator
            if (!$this->validate(AuthValidator::loginRules())) {
                return view('auth/login', $message);
            }

            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');

            if ($this->userService->login($email, $password)) {
                return redirect()->to('/dashboard');
            }
        } catch (\Exception $e) {
            $message = ['error' => 'An error occurred when login : ' . $e->getMessage()];
        }

        return view('auth/login', $message);
    }

    public function logout()
    {
        // Destroy the session
        session()->destroy();
        return redirect()->to('/login'); // Return to the connection screen
    }
}
