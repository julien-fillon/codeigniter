<?php

namespace App\Repositories;

use App\Models\UserModel;
use Exception;

class UserRepository
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * @param string $email
     * @return UserModel|null
     */
    public function findUserByEmail(string $email): UserModel|null
    {
        try {
            return $this->userModel->where('email', $email)->first();
        } catch (Exception $e) {
            $message = 'Error findUserByEmail in findUserByEmail : ' . $e->getMessage();
            log_message('error', $message);
            throw new \Exception($message);
        }
    }
}
