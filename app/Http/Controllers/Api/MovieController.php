<?php declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Enums\HttpStatus;
use App\Http\Controllers\Controller;
use App\Models\Movie;
use App\Http\Requests\{StoreMovieRequest, UpdateMovieRequest};
use App\Http\Resources\{MoviesCollection, MovieResource};
use App\Services\Interfaces\Api\ApiMovieServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use OpenApi\Attributes as OA;

class MovieController extends Controller
{
    public function __construct(
        private readonly ApiMovieServiceInterface $apiMovieService
    ) {}

    #[OA\Get(
        path: '/api/movies',
        summary: 'Get a list of all movies',
        tags: ['Movies'],
        responses: [
            new OA\Response(
                response: HttpStatus::OK->value,
                description: 'Get a list of all movies',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/MoviesCollection'
                )
            )
        ]
    )]
    public function index(): MoviesCollection
    {
        $movies = $this->apiMovieService->getAllApi();

        return new MoviesCollection($movies);
    }

    public function trashed(): MoviesCollection
    {
        $movies = $this->apiMovieService->getTrashed();

        return new MoviesCollection($movies);
    }

    #[OA\Get(
        path: '/api/movies/{id}',
        summary: 'Get movie by ID',
        tags: ['Movies'],
        parameters: [
            new OA\Parameter(
                parameter: 'id',
                name: 'id',
                description: 'Get a single movie by ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(
                    type: 'integer',
                    example: 1,
                )
            )
        ],
        responses: [
            new OA\Response(
                response: HttpStatus::OK->value,
                description: 'Get a single movie by ID',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/MovieResource'
                )
            )
        ]
    )]
    public function show(int $movieId): MovieResource
    {
        $movie = $this->apiMovieService->getByIdApi($movieId);

        return new MovieResource($movie);
    }

    #[OA\Post(
        path: '/api/movies',
        summary: 'Create movie',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: '#/components/schemas/StoreMovieRequest'
            )
        ),
        tags: ['Movies'],
        responses: [
            new OA\Response(
                response: HttpStatus::CREATED->value,
                description: 'Get created movie',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/MovieResource'
                )
            )
        ]
    )]
    public function store(StoreMovieRequest $request): MovieResource
    {
        $movieDTO = $request->toDTO();

        $movie = $this->apiMovieService->createApi($movieDTO);

        return new MovieResource($movie);
    }

    #[OA\Patch(
        path: '/api/movies/{id}',
        summary: 'Update movie by ID',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: '#/components/schemas/StoreMovieRequest'
            )
        ),
        tags: ['Movies'],
        parameters: [
            new OA\Parameter(
                parameter: 'id',
                name: 'id',
                description: 'Update a single movie by ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(
                    type: 'integer',
                    example: 1,
                )
            )
        ],
        responses: [
            new OA\Response(
                response: HttpStatus::OK->value,
                description: 'Get a single movie by ID',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/MovieResource'
                )
            )
        ]
    )]
    public function update(UpdateMovieRequest $request, int $movieId): MovieResource
    {
        $movie = $this->apiMovieService->getByIdApi($movieId);

        Gate::authorize('update', $movie);

        $movieDTO = $request->toDTO();

        $updatedMovie = $this->apiMovieService->updateApi($movieDTO, $movieId);

        return new MovieResource($updatedMovie);
    }

    #[OA\Delete(
        path: '/api/movies/{id}',
        summary: 'Delete movie by ID',
        tags: ['Movies'],
        parameters: [
            new OA\Parameter(
                parameter: 'id',
                name: 'id',
                description: 'Delete a single movie by ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(
                    type: 'integer',
                    example: 1,
                )
            )
        ],
        responses: [
            new OA\Response(
                response: HttpStatus::NO_CONTENT->value,
                description: 'Get a single movie by ID',
                content: []
            )
        ]
    )]
    public function destroy(int $movieId): JsonResponse
    {
        $movie = $this->apiMovieService->getByIdApi($movieId);

        Gate::authorize('destroy', $movie);

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

    #[OA\Delete(
        path: '/api/movies/{id}/force',
        summary: 'Force delete movie by ID',
        tags: ['Movies'],
        parameters: [
            new OA\Parameter(
                parameter: 'id',
                name: 'id',
                description: 'Force delete a single movie by ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(
                    type: 'integer',
                    example: 1,
                )
            )
        ],
        responses: [
            new OA\Response(
                response: HttpStatus::NO_CONTENT->value,
                description: 'Get a single movie by ID',
                content: []
            )
        ]
    )]
    public function forceDelete(int $movieId): JsonResponse
    {
        $movie = $this->apiMovieService->getByIdApi($movieId);

        Gate::authorize('forceDelete', $movie);

        $this->apiMovieService->forceDeleteApi($movieId);

        return response()->json(['success' => true]);
    }
}
