<?php declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\{StoreMovieRequest, UpdateMovieRequest};
use App\Http\Resources\{MovieCollection, MovieResource};
use App\Services\Interfaces\Api\ApiMovieServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class MovieController extends Controller
{
    public function __construct(
        private readonly ApiMovieServiceInterface $apiMovieService
    ) {}

    public function index(): MovieCollection
    {
        $movies = $this->apiMovieService->getAllApi();

        return new MovieCollection($movies);
    }

    public function trashed(): MovieCollection
    {
        $movies = $this->apiMovieService->getTrashed();

        return new MovieCollection($movies);
    }

    public function show(int $movieId): MovieResource|JsonResponse
    {
        $movie = $this->apiMovieService->getByIdApi($movieId);

        if (!$movie) {
            return response()->json(['message' => 'Movie not found'], 404);
        }

        return new MovieResource($movie);
    }

    public function store(StoreMovieRequest $request): MovieResource
    {
        $data = $request->validated();

        $movie = $this->apiMovieService->createApi($data);

        return new MovieResource($movie);
    }

    public function update(UpdateMovieRequest $request, int $movieId): MovieResource|JsonResponse
    {
        $movie = $this->apiMovieService->getByIdApi($movieId);

        Gate::authorize('update', $movie);

        $movie = $this->apiMovieService->updateApi($movieId, $request->validated());

        if (!$movie) {
            return response()->json(['message' => 'Movie not found'], 404);
        }

        return new MovieResource($movie);
    }

    public function destroy(int $movieId): JsonResponse
    {
        $movie = $this->apiMovieService->getByIdApi($movieId);

        Gate::authorize('update', $movie);

        $deleted = $this->apiMovieService->softDeleteApi($movieId);

        if (!$deleted) {
            return response()->json(['message' => 'Movie not found'], 404);
        }

        return response()->json(['message' => 'Deleted']);
    }

    public function restore(int $movieId): JsonResponse
    {
        $result = $this->apiMovieService->restoreApi($movieId);

        if (!$result) {
            return response()->json(['success' => false, 'message' => 'Not found'], 404);
        }

        return response()->json(['success' => true]);
    }

    public function forceDelete(int $movieId): JsonResponse
    {
        $deleted = $this->apiMovieService->forceDeleteApi($movieId);

        if (!$deleted) {
            return response()->json(['success' => false, 'message' => 'Not found'], 404);
        }

        return response()->json(['success' => true]);
    }
}
