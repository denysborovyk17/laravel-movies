<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMovieRequest;
use App\Http\Requests\UpdateMovieRequest;
use App\Services\Interfaces\ApiMovieServiceInterface;
use Illuminate\Http\JsonResponse;

class MovieController extends Controller
{
    public function __construct(
        private ApiMovieServiceInterface $movieApiService
    ) {}

    public function index(): JsonResponse
    {
        return response()->json($this->movieApiService->getAllApi());
    }

    public function show(int $id): JsonResponse
    {
        $movie = $this->movieApiService->getByIdApi($id);
        
        if (!$movie) return response()->json(['message' => 'Movie not found'], 404);

        return response()->json($movie);
    }

    public function store(StoreMovieRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['director_id'] = auth()->id();

        return response()->json($this->movieApiService->createApi($request->validated()), 201);
    }

    public function update(UpdateMovieRequest $request, int $id): JsonResponse
    {
        $movie = $this->movieApiService->updateApi($id, $request->validated());

        if (!$movie) return response()->json(['message' => 'Movie not found'], 404);

        return response()->json($movie);
    }

    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->movieApiService->deleteApi($id);

        if (!$deleted) return response()->json(['message' => 'Movie not found'], 404);

        return response()->json(['message' => 'Deleted']);
    }
}
