<?php declare(strict_types=1);

namespace App\Services\Api;

use App\Enums\{CacheTtl};
use App\Exceptions\MovieNotFoundException;
use App\Models\{Movie, Director};
use App\Repositories\Interfaces\Api\ApiMovieRepositoryInterface;
use App\Services\Interfaces\Api\ApiMovieServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class ApiMovieService implements ApiMovieServiceInterface
{
    public function __construct(
        private readonly ApiMovieRepositoryInterface $apiMovieRepository
    ) {}

    public function getAllApi(): Collection
    {
        return Cache::remember('movies_all', CacheTtl::SHORT->value, function () {
            return $this->apiMovieRepository->allApi();
        });
    }

    public function getByIdApi(int $movieId): Movie
    {
        return Cache::remember("movies_{$movieId}", CacheTtl::SHORT->value, function () use ($movieId) {
            $movie = $this->apiMovieRepository->findApi($movieId);

            if (!$movie) {
                throw new MovieNotFoundException($movieId);
            }

            return $movie;
        });
    }

    public function getTrashed(): Collection
    {
        return Cache::remember('movies_trash', CacheTtl::SHORT->value, function () {
            return $this->apiMovieRepository->getTrashed();
        });
    }

    public function createApi(array $data): Movie
    {
        $director = Director::create(['name' => $data['director']]);
        $data['director_id'] = $director->id;

        $slug = Str::slug($data['title']);
        $data['slug'] = $slug;
        $movie = $this->apiMovieRepository->createApi($data);

        Cache::forget('movies_all');

        $movieId = $movie->id;

        return Cache::tags(['movies'])->remember("movie_{$movieId}", CacheTtl::MEDIUM->value, function () use ($movieId) {
            return $this->apiMovieRepository->findApi($movieId);
        });
    }

    public function updateApi(int $movieId, array $data): Movie|null
    {
        $movie = $this->apiMovieRepository->findApi($movieId);
        if (!$movie) {
            return null;
        }

        $slug = Str::slug($data['title']);
        $data['slug'] = $slug;
        $updatedMovie = $this->apiMovieRepository->updateApi($movieId, $data);

        Cache::forget('movies_all');

        return $updatedMovie;
    }

    public function softDeleteApi(int $movieId): bool
    {
        $movie = $this->apiMovieRepository->findApi($movieId);
        if (!$movie) {
            return false;
        }

        $deletedMovie = $this->apiMovieRepository->softDelete($movieId);

        Cache::forget('movies_all');
        Cache::forget("movies_{$movieId}");

        return $deletedMovie;
    }

    public function restoreApi(int $movieId): Movie|null
    {
        $movie = $this->apiMovieRepository->restore($movieId);
        if (!$movie) {
            return null;
        }

        Cache::forget('movies_all');
        Cache::forget("movies_{$movieId}");

        return $movie;
    }

    public function forceDeleteApi(int $movieId): bool
    {
        $deletedMovie = $this->apiMovieRepository->forceDelete($movieId);
        if (!$deletedMovie) {
            return false;
        }

        Cache::forget('movies_all');
        Cache::forget("movies_{$movieId}");

        return true;
    }
}
