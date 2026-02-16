<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    public function index(): JsonResponse
    {
        $movies = Movie::query()->latest('year')->paginate(10);

        return response()->json([
            'data' => $movies->items(),
            'meta' => [
                'current_page' => $movies->currentPage(),
                'last_page' => $movies->lastPage(),
                'per_page' => $movies->perPage(),
                'total' => $movies->total(),
            ],
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $movie = Movie::query()->findOrFail($id);

        if (!$movie) {
            return response()->json([
                'message' => 'Undefined movie'
            ], 404);
        }

        return response()->json([
            'data' => $movie
        ]);
    }
}
