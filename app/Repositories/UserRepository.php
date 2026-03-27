<?php declare(strict_types=1);

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function find(int $userId): User
    {
        return User::findOrFail($userId);
    }
}
