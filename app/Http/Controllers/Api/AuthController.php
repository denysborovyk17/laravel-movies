<?php declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\{ApiRegisterRequest, ApiLoginRequest};
use App\Http\Resources\AuthResource;
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

    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'user' => $request->user(),
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $this->apiAuthService->logout($request->user());

        return response()->json([
            'message' => 'You successfully logout',
        ]);
    }
}
