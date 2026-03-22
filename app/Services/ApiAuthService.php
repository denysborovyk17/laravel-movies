<?php declare(strict_types=1);

namespace App\Services;

use App\DTO\{AuthDTO, LoginDTO, RegisterDTO};
use App\DTO\UserViewDTO;
use App\Models\User;
use App\Repositories\Interfaces\ApiAuthRepositoryInterface;
use App\Services\Interfaces\ApiAuthServiceInterface;
use Exception;
use Illuminate\Support\Facades\Hash;

class ApiAuthService implements ApiAuthServiceInterface
{
    public function __construct(
        private readonly ApiAuthRepositoryInterface $apiAuthRepository
    ) {}

    public function register(RegisterDTO $userDTO): AuthDTO
    {
        $user = $this->apiAuthRepository->register($userDTO);

        $token = $user->createToken('api_token')->plainTextToken;

        $userViewDTO = new UserViewDTO(
            id: $user->id,
            name: $user->name,
            email: $user->email
        );

        return new AuthDTO(
            user: $userViewDTO,
            token: $token
        );
    }

    public function login(LoginDTO $userDTO): AuthDTO
    {
        $user = $this->apiAuthRepository->login($userDTO);

        if (!$user || !Hash::check($userDTO->getPassword(), $user->password)) {
            throw new Exception('Invalid email or password');
        }

        $token = $user->createToken('api_token')->plainTextToken;

        $userViewDTO = new UserViewDTO(
            id: $user->id,
            name: $user->name,
            email: $user->email
        );

        return new AuthDTO(
            user: $userViewDTO,
            token: $token
        );
    }

    public function logout(User $user): void
    {
        $this->apiAuthRepository->logout($user);
    }
}
