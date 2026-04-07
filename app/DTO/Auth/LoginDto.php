<?php declare(strict_types=1);

namespace App\DTO\Auth;

class LoginDto
{
    public function __construct(
        private readonly string $email,
        private readonly string $password
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            email: (string) $data['email'],
            password: (string) $data['password']
        );
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
