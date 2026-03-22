<?php declare(strict_types=1);

namespace App\Repositories;

use App\DTO\LoginDTO;
use App\DTO\RegisterDTO;
use App\Enums\UserRole;
use App\Models\User;
use App\Repositories\Interfaces\ApiAuthRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class ApiAuthRepository implements ApiAuthRepositoryInterface
{
    public function register(RegisterDTO $userDTO): User
    {
        return User::create([
            'name' => $userDTO->getName(),
            'email' => $userDTO->getEmail(),
            'password' => Hash::make($userDTO->getPassword()),
            'role' => UserRole::USER->value
        ]);
    }

    public function login(LoginDTO $userDTO): User
    {
        return User::query()
            ->where('email', $userDTO->getEmail())
            ->first();
    }

    public function logout(User $user): void
    {
        $user->tokens()->delete();
    }
}
