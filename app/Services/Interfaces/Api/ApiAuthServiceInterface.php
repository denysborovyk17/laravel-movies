<?php declare(strict_types=1);

namespace App\Services\Interfaces\Api;

use App\DTO\Auth\{AuthDto, LoginDto, RegisterDto, UserDataDto};

interface ApiAuthServiceInterface
{
    public function register(RegisterDto $userDTO): AuthDto;

    public function login(LoginDto $userDTO): AuthDto;

    public function me(int $userId): UserDataDto;

    public function logout(int $userId): void;
}
