<?php declare(strict_types=1);

namespace App\Repositories\Api;

use App\DTO\Auth\{RegisterDto, LoginDto};
use App\Models\User;
use App\Repositories\Interfaces\Api\ApiAuthRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class ApiAuthRepository implements ApiAuthRepositoryInterface
{
    public function register(RegisterDto $userDTO): User
    {
        return User::create([
            'name' => $userDTO->getName(),
            'email' => $userDTO->getEmail(),
            'password' => Hash::make($userDTO->getPassword())
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
