<?php declare(strict_types=1);

namespace App\Repositories;

use App\DTO\Auth\RegisterDto;
use App\Models\User;
use App\Repositories\Interfaces\AuthRepositoryInterface;

class AuthRepository implements AuthRepositoryInterface
{
    public function register(RegisterDto $userDTO): User
    {
        return User::create([
            'name' => $userDTO->getName(),
            'email' => $userDTO->getEmail(),
            'password' => bcrypt($userDTO->getPassword())
        ]);
    }
}
