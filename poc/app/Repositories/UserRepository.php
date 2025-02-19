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
     * @return array|null
     */
    public function findUserByEmail(string $email): array|null
    {
        try {
            return $this->userModel->where('email', $email)->first();
        } catch (Exception $e) {
            log_message('error', 'Erreur UserRepository : ' . $e->getMessage());
            return null;
        }
    }
}
