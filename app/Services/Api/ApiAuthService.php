<?php declare(strict_types=1);

namespace App\Services\Api;

use App\DTO\Auth\{AuthDto, LoginDto, RegisterDto, UserDataDto};
use App\Repositories\Interfaces\Api\ApiAuthRepositoryInterface;
use App\Services\Interfaces\Api\ApiAuthServiceInterface;
use Exception;
use Illuminate\Support\Facades\Hash;

class ApiAuthService implements ApiAuthServiceInterface
{
    public function __construct(
        private readonly ApiAuthRepositoryInterface $apiAuthRepository
    ) {}

    public function register(RegisterDto $userDTO): AuthDto
    {
        $user = $this->apiAuthRepository->register($userDTO);

        $token = $user->createToken('api_token')->plainTextToken;

        $userViewDTO = new UserDataDto(
            id: $user->id,
            name: $user->name,
            email: $user->email
        );

        return new AuthDto(
            user: $userViewDTO,
            token: $token
        );
    }

    public function login(LoginDto $userDTO): AuthDto
    {
        $user = $this->apiAuthRepository->login($userDTO);

        if (!$user || !Hash::check($userDTO->getPassword(), $user->password)) {
            throw new Exception('Invalid email or password');
        }

        $token = $user->createToken('api_token')->plainTextToken;

        $userViewDTO = new UserDataDto(
            id: $user->id,
            name: $user->name,
            email: $user->email
        );

        return new AuthDto(
            user: $userViewDTO,
            token: $token
        );
    }

    public function me(int $userId): UserDataDto
    {
        $user = $this->apiAuthRepository->me($userId);
    
        return new UserDataDto(
            id: $user->id,
            name: $user->name,
            email: $user->email
        );
    }

    public function logout(int $userId): void
    {
        $this->apiAuthRepository->logout($userId);
    }
}
