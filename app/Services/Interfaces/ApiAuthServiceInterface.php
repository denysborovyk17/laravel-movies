<?php declare(strict_types=1);

namespace App\Services\Interfaces;

use App\DTO\{Auth, Login, Register};
use App\Models\User;

interface ApiAuthServiceInterface
{
    public function register(Register $userDTO): Auth;

    public function login(Login $userDTO): Auth;

    public function logout(User $user): void;
}
