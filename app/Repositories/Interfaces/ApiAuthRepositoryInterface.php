<?php declare(strict_types=1);

namespace App\Repositories\Interfaces;

use App\DTO\{Register, Login};
use App\Models\User;

interface ApiAuthRepositoryInterface
{
    public function register(Register $userDTO): User;

    public function login(Login $userDTO): User;

    public function me(int $userId): User;

    public function logout(int $userId): void;
}
