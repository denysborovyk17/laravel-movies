<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMovieRequest;
use App\Http\Requests\UpdateMovieRequest;
use App\Http\Resources\MovieCollection;
use App\Http\Resources\MovieResource;
use App\Models\Director;
use App\Services\Interfaces\ApiMovieServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class MovieController extends Controller
{
    public function __construct(
        private readonly ApiMovieServiceInterface $movieApiService
    ) {}

    public function index(): MovieCollection
    {
        $movies = $this->movieApiService->getAllApi();

        return new MovieCollection($movies);
    }

    public function trashed(): MovieCollection
    {
        $movies = $this->movieApiService->getTrashed();

        return new MovieCollection($movies);
    }

    public function show(int $id): MovieResource|JsonResponse
    {
        $movie = $this->movieApiService->getByIdApi($id);

        if (! $movie) {
            return response()->json(['message' => 'Movie not found'], 404);
        }

        return new MovieResource($movie);
    }

    public function store(StoreMovieRequest $request, int $id): MovieResource
    {
        $movie = $this->movieApiService->getByIdApi($id);

        Gate::authorize('update', $movie);

        $data = $request->validated();

        $director = Director::firstOrCreate(['name' => $data['director']]);
        $data['director_id'] = $director->id;

        $movie = $this->movieApiService->createApi($data);

        return new MovieResource($movie);
    }

    public function update(UpdateMovieRequest $request, int $id): MovieResource|JsonResponse
    {
        $movie = $this->movieApiService->getByIdApi($id);

        Gate::authorize('update', $movie);

        $movie = $this->movieApiService->updateApi($id, $request->validated());

        if (! $movie) {
            return response()->json(['message' => 'Movie not found'], 404);
        }

        return new MovieResource($movie);
    }

    public function destroy(int $id): JsonResponse
    {
        $movie = $this->movieApiService->getByIdApi($id);

        Gate::authorize('update', $movie);

        $deleted = $this->movieApiService->softDeleteApi($id);

        if (! $deleted) {
            return response()->json(['message' => 'Movie not found'], 404);
        }

        return response()->json(['message' => 'Deleted']);
    }

    public function restore(int $id): JsonResponse
    {
        $result = $this->movieApiService->restoreApi($id);

        if (! $result) {
            return response()->json(['success' => false, 'message' => 'Not found'], 404);
        }

        return response()->json(['success' => true]);
    }

    public function forceDelete(int $id): JsonResponse
    {
        $deleted = $this->movieApiService->forceDeleteApi($id);

        if (! $deleted) {
            return response()->json(['success' => false, 'message' => 'Not found'], 404);
        }

        return response()->json(['success' => true]);
    }
}
