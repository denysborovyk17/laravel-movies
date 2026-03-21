<?php declare(strict_types=1);

namespace App\Exceptions;

use App\Enums\HttpStatus;
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
            ], HttpStatus::TOO_MANY_REQUESTS->value);
        });

        $exceptions->render(function (ApiException $e): JsonResponse {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'errors' => $e->errors,
            ], $e->status);
        });

        $exceptions->render(function (ModelNotFoundException $e): JsonResponse {
            return response()->json([
                'success' => false,
                'message' => 'Resource not found',
            ], HttpStatus::NOT_FOUND->value);
        });

        $exceptions->render(function (AuthorizationException $e): JsonResponse {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage() ?: 'Forbidden',
            ], HttpStatus::FORBIDDEN->value);
        });

        $exceptions->render(function (AuthenticationException $e): JsonResponse {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], HttpStatus::UNAUTHORIZED->value);
        });

        $exceptions->render(function (ValidationException $e): JsonResponse {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors(),
            ], HttpStatus::UNPROCESSABLE_ENTITY->value);
        });

        $exceptions->render(function (Throwable $e): JsonResponse {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], HttpStatus::INTERNAL_SERVER_ERROR->value);
        });
    }
}
