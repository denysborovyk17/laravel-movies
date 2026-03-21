<?php declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\{ApiRegisterRequest, ApiLoginRequest};
use App\Services\ApiAuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        private readonly ApiAuthService $apiAuthService
    ) {}

    public function register(ApiRegisterRequest $request): JsonResponse
    {
        $authDTO = $this->apiAuthService->register($request->toDTO());

        return response()->json($authDTO);
    }

    public function login(ApiLoginRequest $request): JsonResponse
    {
        $authDTO = $this->apiAuthService->login($request->toDTO());

        return response()->json($authDTO);
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
