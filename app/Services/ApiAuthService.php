<?php declare(strict_types=1);

namespace App\Services;

use App\DTO\{Auth, Login, Register, UserData};
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

    public function register(Register $userDTO): Auth
    {
        $user = $this->apiAuthRepository->register($userDTO);

        $token = $user->createToken('api_token')->plainTextToken;

        $userViewDTO = new UserData(
            id: $user->id,
            name: $user->name,
            email: $user->email
        );

        return new Auth(
            user: $userViewDTO,
            token: $token
        );
    }

    public function login(Login $userDTO): Auth
    {
        $user = $this->apiAuthRepository->login($userDTO);

        if (!$user || !Hash::check($userDTO->getPassword(), $user->password)) {
            throw new Exception('Invalid email or password');
        }

        $token = $user->createToken('api_token')->plainTextToken;

        $userViewDTO = new UserData(
            id: $user->id,
            name: $user->name,
            email: $user->email
        );

        return new Auth(
            user: $userViewDTO,
            token: $token
        );
    }

    public function logout(User $user): void
    {
        $this->apiAuthRepository->logout($user);
    }
}
