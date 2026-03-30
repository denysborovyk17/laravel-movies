<?php declare(strict_types=1);

namespace App\Services\Interfaces\Api;

use App\DTO\Auth\{AuthDto, LoginDto, RegisterDto, UserDataDto};
use App\Models\User;

interface ApiAuthServiceInterface
{
    public function register(RegisterDto $userDTO): array;

    public function login(LoginDto $userDTO): array;

    public function logout(int $userId): void;
}
