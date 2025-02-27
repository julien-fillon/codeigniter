<?php

namespace App\Repositories;

use App\Entities\UserEntity;
use App\Models\UserModel;
use CodeIgniter\Database\Exceptions\DatabaseException;
use Exception;

class UserRepository
{
    /**
     * @var UserModel $userModel User model instance
     */
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * Recovers an user by his Email.
     *
     * @param int $id
     * @return UserEntity|null The user data, or null if not found.
     * @throws DatabaseException
     */
    public function findUserByEmail(string $email): UserEntity|null
    {
        try {
            return $this->userModel->where('email', $email)->first();
        } catch (Exception $e) {
            $message = 'Error fetching the user with Email ' . $email . ': ' . $e->getMessage();
            log_message('error', $message);
            throw new DatabaseException($message);
        }
    }
}
