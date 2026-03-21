<?php declare(strict_types=1);

namespace App\Services;

use App\Enums\{HttpStatus, CacheTtl};
use App\Exceptions\ApiException;
use App\Models\{Movie, Director};
use App\Repositories\Interfaces\MovieRepositoryInterface;
use App\Services\Interfaces\ApiMovieServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class ApiMovieService implements ApiMovieServiceInterface
{
    public function __construct(
        private readonly MovieRepositoryInterface $movieRepository
    ) {}

    public function getAllApi(): Collection
    {
        return Cache::remember('movies_all', CacheTtl::SHORT->value, function () {
            return $this->movieRepository->allApi();
        });
    }

    public function getByIdApi(int $movieId): Movie|null
    {
        return Cache::remember("movies_{$movieId}", CacheTtl::SHORT->value, function () use ($movieId) {
            $movie = $this->movieRepository->findApi($movieId);

            if (! $movie) {
                throw new ApiException("Movie with ID $movieId not found", HttpStatus::NOT_FOUND->value);
            }

            return $movie;
        });
    }

    public function getTrashed(): Collection
    {
        return Cache::remember('movies_trash', CacheTtl::SHORT->value, function () {
            return $this->movieRepository->getTrashed();
        });
    }

    public function createApi(array $data): Movie
    {
        $director = Director::firstOrCreate(['name' => $data['director']]);

        $data['director_id'] = $director->id;
        unset($data['director']);

        $slug = Str::slug($data['title']);
        $data['slug'] = $slug;
        $movie = $this->movieRepository->createApi($data);

        Cache::forget('movies_all');

        $movieId = $movie->id;

        return Cache::tags(['movies'])->remember("movie_{$movieId}", CacheTtl::MEDIUM->value, function () use ($movieId) {
            return $this->movieRepository->findApi($movieId);
        });
    }

    public function updateApi(int $movieId, array $data): Movie|null
    {
        $movie = $this->movieRepository->findApi($movieId);
        if (! $movie) {
            return null;
        }

        $slug = Str::slug($data['title']);
        $data['slug'] = $slug;
        $updatedMovie = $this->movieRepository->updateApi($movie, $data);

        Cache::forget('movies_all');

        return $updatedMovie;
    }

    public function softDeleteApi(int $movieId): bool
    {
        $movie = $this->movieRepository->findApi($movieId);
        if (! $movie) {
            return false;
        }

        $deletedMovie = $this->movieRepository->softDelete($movie);

        Cache::forget('movies_all');
        Cache::forget("movies_{$movieId}");

        return $deletedMovie;
    }

    public function restoreApi(int $movieId): Movie|null
    {
        $movie = $this->movieRepository->restore($movieId);
        if (! $movie) {
            return null;
        }

        Cache::forget('movies_all');
        Cache::forget("movies_{$movieId}");

        return $movie;
    }

    public function forceDeleteApi(int $movieId): bool
    {
        $deletedMovie = $this->movieRepository->forceDelete($movieId);
        if (! $deletedMovie) {
            return false;
        }

        Cache::forget('movies_all');
        Cache::forget("movies_{$movieId}");

        return true;
    }
}
