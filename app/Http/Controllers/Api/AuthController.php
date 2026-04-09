<?php declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\{ApiRegisterRequest, ApiLoginRequest};
use App\Http\Resources\UserResource;
use App\Services\Interfaces\Api\ApiAuthServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        private readonly ApiAuthServiceInterface $apiAuthService
    ) {}

    public function register(ApiRegisterRequest $request): JsonResponse
    {
        $result = $this->apiAuthService->register($request->toDTO());

        $user = $result['user'];
        $token = $result['token'];

        return response()->json([
            'user' => new UserResource($user),
            'token' => $token
        ]);
    }

    public function login(ApiLoginRequest $request): JsonResponse
    {
        $result = $this->apiAuthService->login($request->toDTO());

        $user = $result['user'];
        $token = $result['token'];

        return response()->json([
            'user' => new UserResource($user),
            'token' => $token
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'user' => new UserResource($request->user())
        ]);
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
