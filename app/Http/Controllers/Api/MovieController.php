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

    public function show(int $movieId): MovieResource
    {
        $movie = $this->apiMovieService->getByIdApi($movieId);

        return new MovieResource($movie);
    }

    public function store(StoreMovieRequest $request): MovieResource
    {
        $movieDTO = $request->toDTO();

        $movie = $this->apiMovieService->createApi($movieDTO);

        return new MovieResource($movie);
    }

    public function update(UpdateMovieRequest $request, int $movieId): MovieResource
    {
        $movie = $this->apiMovieService->getByIdApi($movieId);

        Gate::authorize('update', $movie);

        $movieDTO = $request->toDTO();

        $updatedMovie = $this->apiMovieService->updateApi($movieDTO, $movieId);

        return new MovieResource($updatedMovie);
    }

    public function destroy(int $movieId): JsonResponse
    {
        $movie = $this->apiMovieService->getByIdApi($movieId);

        Gate::authorize('delete', $movie);

        $this->apiMovieService->softDeleteApi($movieId);

        return response()->json(['message' => 'Deleted']);
    }

    public function restore(int $movieId): JsonResponse
    {
        $movie = $this->apiMovieService->getByIdApi($movieId);

        Gate::authorize('restore', $movie);

        $this->apiMovieService->restoreApi($movieId);

        return response()->json(['success' => true]);
    }

    public function forceDelete(int $movieId): JsonResponse
    {
        $this->apiMovieService->forceDeleteApi($movieId);

        return response()->json(['success' => true]);
    }
}
