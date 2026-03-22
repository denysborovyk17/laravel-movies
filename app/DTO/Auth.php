<?php declare(strict_types=1);

namespace App\DTO;

class Auth
{
    public function __construct(
        private readonly UserData $user,
        private readonly string $token
    ) {}

    public function getUser(): UserData
    {
        return $this->user;
    }

    public function getToken(): string
    {
        return $this->token;
    }
}
