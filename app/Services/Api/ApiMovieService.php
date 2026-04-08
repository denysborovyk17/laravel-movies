<?php declare(strict_types=1);

namespace App\Services\Api;

use App\DTO\Admin\MovieDataDto;
use App\Exceptions\MovieNotFoundException;
use App\Services\SlugService;
use App\Models\Movie;
use App\Repositories\Interfaces\Api\{ApiMovieRepositoryInterface, ApiDirectorRepositoryInterface};
use App\Services\Interfaces\Api\ApiMovieServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class ApiMovieService implements ApiMovieServiceInterface
{
    public function __construct(
        private readonly ApiMovieRepositoryInterface $apiMovieRepository,
        private readonly ApiDirectorRepositoryInterface $apiDirectorRepository,
        private readonly SlugService $slugService,
    ) {}

    public function getAllApi(): Collection
    {
        return Cache::remember('movies_all', config('custom.cache_ttl.short'), function () {
            return $this->apiMovieRepository->allApi();
        });
    }

    public function getByIdApi(int $movieId): Movie
    {
        return Cache::remember("movies_{$movieId}", config('custom.cache_ttl.short'), function () use ($movieId) {
            $movie = $this->apiMovieRepository->findApi($movieId);

            if (!$movie) {
                throw new MovieNotFoundException($movieId);
            }

            return $movie;
        });
    }

    public function getTrashed(): Collection
    {
        return Cache::remember('movies_trash', config('custom.cache_ttl.short'), function () {
            return $this->apiMovieRepository->getTrashed();
        });
    }

    public function createApi(MovieDataDto $movieDTO): Movie
    {
        $data = $this->buildDataApi($movieDTO, null);

        $movie = $this->apiMovieRepository->createApi($data);

        Cache::forget('movies_all');

        return $movie;
    }

    public function updateApi(MovieDataDto $movieDTO, int $movieId): Movie
    {
        $movie = $this->apiMovieRepository->findApi($movieId);

        if (!$movie) {
            throw new MovieNotFoundException($movieId);
        }

        $data = $this->buildDataApi($movieDTO, $movie);

        Cache::forget('movies_all');
        Cache::forget("movies_{$movieId}");

        $movie->update($data);

        return $movie;
    }

    public function softDeleteApi(int $movieId): void
    {
        $movie = $this->apiMovieRepository->findApi($movieId);
        if (!$movie) {
            throw new MovieNotFoundException($movieId);
        }

        $this->apiMovieRepository->softDelete($movieId);

        Cache::forget('movies_all');
        Cache::forget("movies_{$movieId}");
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

    public function buildDataApi(MovieDataDto $movieDTO, ?Movie $movie): array
    {
        $director = $this->apiDirectorRepository->findOrCreate($movieDTO->getDirector());
        $slug = $this->slugService->generateUnique($movieDTO->getTitle(), Movie::class, $movie?->id);

        return [
            'title' => $movieDTO->getTitle(),
            'director_id' => $director->id,
            'slug' => $slug,
            'description' => $movieDTO->getDescription(),
            'year' => $movieDTO->getYear(),
            'genre' => $movieDTO->getGenre(),
            'rating' => $movieDTO->getRating(),
            'status' => $movieDTO->getStatus(),
        ];
    }
}
