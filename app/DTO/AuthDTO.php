<?php declare(strict_types=1);

namespace App\DTO;

class AuthDTO
{
    public function __construct(
        private readonly UserViewDTO $user,
        private readonly string $token
    ) {}

    public function getUser(): UserViewDTO
    {
        return $this->user;
    }

    public function getToken(): string
    {
        return $this->token;
    }
}
