<?php declare(strict_types=1);

namespace App\DTO\Auth;

use App\Http\Requests\Api\Auth\ApiLoginRequest;

class LoginDto
{
    public function __construct(
        private readonly string $email,
        private readonly string $password
    ) {}

    public static function fromRequest(ApiLoginRequest $request): self
    {
        return new self(
            email: $request->validated('email'),
            password: $request->validated('password')
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
