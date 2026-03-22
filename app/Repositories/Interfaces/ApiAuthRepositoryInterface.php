<?php declare(strict_types=1);

namespace App\Repositories\Interfaces;

use App\DTO\LoginDTO;
use App\DTO\RegisterDTO;
use App\Models\User;

interface ApiAuthRepositoryInterface
{
    public function register(RegisterDTO $userDTO): User;

    public function login(LoginDTO $userDTO): User;

    public function logout(User $user): void;
}
