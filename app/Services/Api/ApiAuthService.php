<?php declare(strict_types=1);

namespace App\Services\Api;

use App\DTO\Auth\{AuthDto, LoginDto, RegisterDto, UserDataDto};
use App\Models\User;
use App\Repositories\Interfaces\Api\ApiAuthRepositoryInterface;
use App\Services\Interfaces\Api\ApiAuthServiceInterface;
use Exception;
use Illuminate\Support\Facades\Hash;

class ApiAuthService implements ApiAuthServiceInterface
{
    public function __construct(
        private readonly ApiAuthRepositoryInterface $apiAuthRepository
    ) {}

    public function register(RegisterDto $userDTO): array
    {
        $user = $this->apiAuthRepository->register($userDTO);

        $token = $user->createToken('api_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token
        ];
    }

    public function login(LoginDto $userDTO): array
    {
        $user = $this->apiAuthRepository->login($userDTO);

        if (!$user || !Hash::check($userDTO->getPassword(), $user->password)) {
            throw new Exception('Invalid email or password');
        }

        $token = $user->createToken('api_token')->plainTextToken;

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
