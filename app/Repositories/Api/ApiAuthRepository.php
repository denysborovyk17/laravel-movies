<?php declare(strict_types=1);

namespace App\Repositories\Api;

use App\DTO\{AuthDto, RegisterDto, LoginDto, UserDataDto};
use App\Enums\UserRole;
use App\Models\User;
use App\Repositories\Interfaces\ApiAuthRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class ApiAuthRepository implements ApiAuthRepositoryInterface
{
    public function register(RegisterDto $userDTO): User
    {
        return User::create([
            'name' => $userDTO->getName(),
            'email' => $userDTO->getEmail(),
            'password' => Hash::make($userDTO->getPassword()),
            'role' => UserRole::USER->value
        ]);
    }

    public function login(LoginDto $userDTO): User
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
        $user = User::find($userId);
        
        if ($user) {
            $user->tokens()->delete();   
        }
    }
}
