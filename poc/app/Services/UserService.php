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
     * Connect an user
     * 
     * @param string email
     * @param string password
     * @return boolean
     * @throws \RuntimeException
     */
    public function login(string $email, string $password): bool
    {
        try {
            $user = $this->userRepo->findUserByEmail($email);
            if ($user && password_verify($password, $user->password)) {
                session()->set(['isLoggedIn' => true, 'user_id' => $user->id, 'user_name' => $user->name]);
                return true;
            }
            return false;
        } catch (\Exception $e) {
            throw new \RuntimeException("Error when login user :" . $e->getMessage());
        }
    }
}
