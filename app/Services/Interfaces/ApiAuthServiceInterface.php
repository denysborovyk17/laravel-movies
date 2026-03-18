<?php

namespace App\Services\Interfaces;

use App\Models\User;

interface ApiAuthServiceInterface
{
    public function register(array $data): array;

    public function login(array $data): array;

    public function logout(User $user): void;
}
