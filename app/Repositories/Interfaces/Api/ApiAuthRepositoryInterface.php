<?php declare(strict_types=1);

namespace App\Repositories\Interfaces\Api;

use App\DTO\{RegisterDto, LoginDto};
use App\Models\User;

interface ApiAuthRepositoryInterface
{
    public function register(RegisterDto $userDTO): User;

    public function login(LoginDto $userDTO): User;

    public function me(int $userId): User;

    public function logout(int $userId): void;
}
