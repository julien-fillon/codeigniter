<?php

namespace App\Services;

use App\Repositories\UserRepository;

class UserService
{
    protected $userRepo;

    public function __construct()
    {
        $this->userRepo = new UserRepository();
    }

    /**
     * @param string email
     * @param string password
     * @return boolean
     */
    public function login(string $email, string $password)
    {
        $user = $this->userRepo->findUserByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            session()->set(['isLoggedIn' => true, 'user_id' => $user['id'], 'user_name' => $user['name']]);
            return true;
        }
        return false;
    }
}
