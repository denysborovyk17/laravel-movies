<?php

declare(strict_types=1);

namespace App\Exceptions;

use App\Exceptions\ApiException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Throwable;

class HandlerException
{
    public static function register(Exceptions $exceptions): void
    {
        static::registerReporters($exceptions);
        static::registerRenderers($exceptions);
    }

    public static function registerReporters(Exceptions $exceptions): void
    {
        $exceptions->report(function (ApiException $e): bool {
            if ($e->status >= 400 && $e->status < 500) {
                return false;
            }

            Log::error('Api Exception', [
                'message' => $e->getMessage(),
                'status' => $e->status,
                'errors' => $e->errors,
            ]);

            return true;
        });
    }

    public static function registerRenderers(Exceptions $exceptions): void
    {
        $exceptions->render(function (ThrottleRequestsException $e): JsonResponse {
            return response()->json([
                'success' => false,
                'message' => 'Too many requests. Please try again later.',
            ], 429);
        });

        $exceptions->render(function (ApiException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'errors' => $e->errors,
            ], $e->status);
        });

        $exceptions->render(function (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Resource not found',
            ]);
        });

        $exceptions->render(function (AuthorizationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage() ?: 'Forbidden',
            ], 403);
        });

        $exceptions->render(function (AuthenticationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorize',
            ], 401);
        });

        $exceptions->render(function (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors(),
            ], 422);
        });

        $exceptions->render(function (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        });
    }
}
