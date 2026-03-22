<?php declare(strict_types=1);

namespace App\Repositories;

use App\DTO\{Auth, Register, Login, UserData};
use App\Enums\UserRole;
use App\Models\User;
use App\Repositories\Interfaces\ApiAuthRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class ApiAuthRepository implements ApiAuthRepositoryInterface
{
    public function register(Register $userDTO): User
    {
        return User::create([
            'name' => $userDTO->getName(),
            'email' => $userDTO->getEmail(),
            'password' => Hash::make($userDTO->getPassword()),
            'role' => UserRole::USER->value
        ]);
    }

    public function login(Login $userDTO): User
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
