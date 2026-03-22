<?php declare(strict_types=1);

namespace App\DTO;

class LoginDTO
{
    public function __construct(
        private readonly string $email,
        private readonly string $password
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            email: $data['email'],
            password: $data['password']
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
