<?php declare(strict_types=1);

namespace App\Repositories\Api;

use Laravel\Passport\Token;
use App\DTO\Auth\{RegisterDto, LoginDto};
use App\Models\User;
use App\Repositories\Interfaces\Api\ApiAuthRepositoryInterface;

class ApiAuthRepository implements ApiAuthRepositoryInterface
{
    public function register(RegisterDto $userDTO): User
    {
        return User::create([
            'name' => $userDTO->getName(),
            'email' => $userDTO->getEmail(),
            'password' => bcrypt($userDTO->getPassword())
        ]);
    }

    public function login(LoginDto $userDTO): User|null
    {
        return User::query()
            ->where('email', $userDTO->getEmail())
            ->first();
    }

    public function me(int $userId): User
    {
        return User::find($userId);
    }

    public function logout(int $userId): void
    {
        User::find($userId)->tokens()->each(function (Token $token) {
            $token->revoke();
            $token->refreshToken?->revoke();
        });
    }
}
