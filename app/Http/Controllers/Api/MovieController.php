<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMovieRequest;
use App\Http\Requests\UpdateMovieRequest;
use App\Http\Resources\MovieCollection;
use App\Http\Resources\MovieResource;
use App\Services\Interfaces\ApiMovieServiceInterface;
use Illuminate\Http\JsonResponse;

class MovieController extends Controller
{
    public function __construct(
        private ApiMovieServiceInterface $movieApiService
    ) {}

    public function index(): MovieCollection
    {
        $movies = $this->movieApiService->getAllApi();
    
        return new MovieCollection($movies);
    }

    public function show(int $id): MovieResource | JsonResponse
    {
        $movie = $this->movieApiService->getByIdApi($id);
        
        if (!$movie) return response()->json(['message' => 'Movie not found'], 404);

        return new MovieResource($movie);
    }

    public function store(StoreMovieRequest $request): MovieResource
    {
        $data = $request->validated();
        $data['director_id'] = auth()->id();

        $movie = $this->movieApiService->createApi($data);

        return new MovieResource($movie);
    }

    public function update(UpdateMovieRequest $request, int $id): MovieResource | JsonResponse
    {
        $movie = $this->movieApiService->updateApi($id, $request->validated());

        if (!$movie) return response()->json(['message' => 'Movie not found'], 404);

        return new MovieResource($movie);
    }

    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->movieApiService->deleteApi($id);

        if (!$deleted) return response()->json(['message' => 'Movie not found'], 404);

        return response()->json(['message' => 'Deleted']);
    }
}
