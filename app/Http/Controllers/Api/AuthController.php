<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\{ApiRegisterRequest, ApiLoginRequest};
use App\Models\User;
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
        $resultRegister = $this->apiAuthService->register($request->validated());

        return response()->json($resultRegister, 201);
    }

    public function login(ApiLoginRequest $request): JsonResponse
    {
        $resultLogin = $this->apiAuthService->login($request->validated());

        return response()->json($resultLogin);
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
