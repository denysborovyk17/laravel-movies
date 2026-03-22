<?php declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\{ApiRegisterRequest, ApiLoginRequest};
use App\Http\Resources\{AuthResource, UserDataResource};
use App\Services\Interfaces\ApiAuthServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        private readonly ApiAuthServiceInterface $apiAuthService
    ) {}

    public function register(ApiRegisterRequest $request): AuthResource
    {
        $authDTO = $this->apiAuthService->register($request->toDTO());

        return new AuthResource($authDTO);
    }

    public function login(ApiLoginRequest $request): AuthResource
    {
        $authDTO = $this->apiAuthService->login($request->toDTO());

        return new AuthResource($authDTO);
    }

    public function me(Request $request): UserDataResource
    {
        $userData = $this->apiAuthService->me($request->user()->id);
    
        return new UserDataResource($userData);
    }

    public function logout(Request $request): JsonResponse
    {
        $userId = $request->user()->id;
        
        $this->apiAuthService->logout($userId);

        return response()->json([
            'message' => 'You successfully logout',
        ]);
    }
}
