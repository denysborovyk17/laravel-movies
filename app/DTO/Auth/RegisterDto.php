<?php declare(strict_types=1);

namespace App\DTO\Auth;

use App\Http\Requests\Api\Auth\ApiRegisterRequest;

class RegisterDto
{
    public function __construct(
        private readonly string $name,
        private readonly string $email,
        private readonly string $password
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            email: $data['email'],
            password: $data['password']
        );
    }

    public function getName(): string
    {
        return $this->name;
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
