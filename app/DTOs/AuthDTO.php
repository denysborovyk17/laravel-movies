<?php

declare(strict_types=1);

namespace App\DTOs;

class AuthDTO
{
    public function __construct(
        public readonly UserViewDTO $user,
        public readonly string $token
    ) {}
}
