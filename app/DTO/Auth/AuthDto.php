<?php declare(strict_types=1);

namespace App\DTO;

class AuthDto
{
    public function __construct(
        private readonly UserDataDto $user,
        private readonly string $token
    ) {}

    public function getUser(): UserDataDto
    {
        return $this->user;
    }

    public function getToken(): string
    {
        return $this->token;
    }
}
