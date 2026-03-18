<?php

declare(strict_types=1);

namespace App\Services;

use App\DTOs\{AuthDTO, LoginDTO, RegisterDTO};
use App\DTOs\UserViewDTO;
use App\Enums\UserRole;
use App\Models\User;
use App\Services\Interfaces\ApiAuthServiceInterface;
use Exception;
use Illuminate\Support\Facades\Hash;

class ApiAuthService implements ApiAuthServiceInterface
{
    public function __construct(
        //
    ) {}

    public function register(RegisterDTO $userDTO): AuthDTO
    {
        $user = User::create([
            'name' => $userDTO->name,
            'email' => $userDTO->email,
            'password' => Hash::make($userDTO->password),
            'role' => UserRole::USER->value
        ]);

        $token = $user->createToken('api_token')->plainTextToken;

        $userViewDTO = new UserViewDTO(
            id: $user->id,
            name: $user->name,
            email: $user->email
        );

        return new AuthDTO(
            user: $userViewDTO,
            token: $token
        );
    }

    public function login(LoginDTO $userDTO): AuthDTO
    {
        $user = User::query()
            ->where('email', $userDTO->email)
            ->first();

        if (! $user || ! Hash::check($userDTO->password, $user->password)) {
            throw new Exception('Invalid email or password');
        }

        $token = $user->createToken('api_token')->plainTextToken;

        $userViewDTO = new UserViewDTO(
            id: $user->id,
            name: $user->name,
            email: $user->email
        );

        return new AuthDTO(
            user: $userViewDTO,
            token: $token
        );
    }

    public function logout(User $user): void
    {
        $user->tokens()->delete();
    }
}
