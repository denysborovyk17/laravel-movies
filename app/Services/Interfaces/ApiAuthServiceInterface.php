<?php declare(strict_types=1);

namespace App\Services\Interfaces;

use App\DTO\{Auth, Login, Register, UserData};
use App\Models\User;

interface ApiAuthServiceInterface
{
    public function register(Register $userDTO): Auth;

    public function login(Login $userDTO): Auth;

    public function me(int $userId): UserData;

    public function logout(int $userId): void;
}
