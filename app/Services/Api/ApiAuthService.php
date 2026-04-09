<?php declare(strict_types=1);

namespace App\Services\Api;

use App\Exceptions\InvalidCredentialsException;
use App\DTO\Auth\{LoginDto, RegisterDto};
use App\Repositories\Interfaces\Api\ApiAuthRepositoryInterface;
use App\Services\Interfaces\Api\ApiAuthServiceInterface;
use Exception;

class ApiAuthService implements ApiAuthServiceInterface
{
    public function __construct(
        private readonly ApiAuthRepositoryInterface $apiAuthRepository
    ) {}

    public function register(RegisterDto $userDTO): array
    {
        $user = $this->apiAuthRepository->register($userDTO);

        $token = $user->createToken('api_token')->accessToken;

        return [
            'user' => $user,
            'token' => $token
        ];
    }

    /**
     * @throws Exception
     */
    public function login(LoginDto $userDTO): array
    {
        $user = $this->apiAuthRepository->login($userDTO);

        if (!$user || !bcrypt($userDTO->getPassword(), $user->password)) {
            throw new InvalidCredentialsException('Invalid email or password');
        }

        $token = $user->createToken('api_token')->accessToken;

        return [
            'user' => $user,
            'token' => $token
        ];
    }

    public function logout(int $userId): void
    {
        $this->apiAuthRepository->logout($userId);
    }
}
