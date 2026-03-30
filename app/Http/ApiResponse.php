<?php declare(strict_types=1);

namespace App\Http;

use App\Enums\HttpStatus;
use Illuminate\Http\JsonResponse;

class ApiResponse
{
    public static function success(mixed $data, HttpStatus $status = HttpStatus::OK): JsonResponse
    {
        return response()->json([
            'data' => $data,
        ], $status->value);
    }
}
