<?php declare(strict_types=1);

namespace App\Services;

use App\Enums\{HttpStatus, CacheTtl};
use App\Exceptions\ApiException;
use App\Models\{Movie, Director};
use App\Repositories\Interfaces\ApiMovieRepositoryInterface;
use App\Services\Interfaces\ApiMovieServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class ApiMovieService implements ApiMovieServiceInterface
{
    public function __construct(
        private readonly ApiMovieRepositoryInterface $apiMovieRepositoryInterface
    ) {}

    public function getAllApi(): Collection
    {
        return Cache::remember('movies_all', CacheTtl::SHORT->value, function () {
            return $this->apiMovieRepositoryInterface->allApi();
        });
    }

    public function getByIdApi(int $movieId): Movie|null
    {
        return Cache::remember("movies_{$movieId}", CacheTtl::SHORT->value, function () use ($movieId) {
            $movie = $this->apiMovieRepositoryInterface->findApi($movieId);

            if (!$movie) {
                throw new ApiException("Movie with ID $movieId not found", HttpStatus::NOT_FOUND->value);
            }

            return $movie;
        });
    }

    public function getTrashed(): Collection
    {
        return Cache::remember('movies_trash', CacheTtl::SHORT->value, function () {
            return $this->apiMovieRepositoryInterface->getTrashed();
        });
    }

    public function createApi(array $data): Movie
    {
        $director = Director::firstOrCreate(['name' => $data['director']]);

        $data['director_id'] = $director->id;
        unset($data['director']);

        $slug = Str::slug($data['title']);
        $data['slug'] = $slug;
        $movie = $this->apiMovieRepositoryInterface->createApi($data);

        Cache::forget('movies_all');

        $movieId = $movie->id;

        return Cache::tags(['movies'])->remember("movie_{$movieId}", CacheTtl::MEDIUM->value, function () use ($movieId) {
            return $this->apiMovieRepositoryInterface->findApi($movieId);
        });
    }

    public function updateApi(int $movieId, array $data): Movie|null
    {
        $movie = $this->apiMovieRepositoryInterface->findApi($movieId);
        if (!$movie) {
            return null;
        }

        $slug = Str::slug($data['title']);
        $data['slug'] = $slug;
        $updatedMovie = $this->apiMovieRepositoryInterface->updateApi($movieId, $data);

        Cache::forget('movies_all');

        return $updatedMovie;
    }

    public function softDeleteApi(int $movieId): bool
    {
        $movie = $this->apiMovieRepositoryInterface->findApi($movieId);
        if (!$movie) {
            return false;
        }

        $deletedMovie = $this->apiMovieRepositoryInterface->softDelete($movieId);

        Cache::forget('movies_all');
        Cache::forget("movies_{$movieId}");

        return $deletedMovie;
    }

    public function restoreApi(int $movieId): Movie|null
    {
        $movie = $this->apiMovieRepositoryInterface->restore($movieId);
        if (!$movie) {
            return null;
        }

        Cache::forget('movies_all');
        Cache::forget("movies_{$movieId}");

        return $movie;
    }

    public function forceDeleteApi(int $movieId): bool
    {
        $deletedMovie = $this->apiMovieRepositoryInterface->forceDelete($movieId);
        if (!$deletedMovie) {
            return false;
        }

        Cache::forget('movies_all');
        Cache::forget("movies_{$movieId}");

        return true;
    }
}
