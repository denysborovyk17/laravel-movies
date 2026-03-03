<?php

namespace App\Services;

use App\Exceptions\ApiException;
use App\Models\Director;
use App\Models\Movie;
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
        return Cache::remember('movies_all', 60, function () {
            return $this->movieRepository->allApi();
        });
    }

    public function getTrashed(): Collection
    {
        return Cache::remember('movies_trash', 60, function () {
            return $this->movieRepository->getTrashed();
        });
    }

    public function getByIdApi(int $id): Movie|null
    {
        return Cache::remember("movies_{$id}", 60, function () use ($id) {
            $movie = $this->movieRepository->findApi($id);

            if (!$movie) {
                throw new ApiException("Movie with ID $id not found", 404);
            }

            return $movie;
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

        $id = $movie->id;

        return Cache::tags(['movies'])->remember("movie_{$id}", 120, function () use ($id) {
            return $this->movieRepository->findApi($id);
        });
    }

    public function updateApi(int $id, array $data): Movie|null
    {
        $movie = $this->movieRepository->findApi($id);
        if (!$movie) {
            return null;
        }

        $slug = Str::slug($data['title']);
        $data['slug'] = $slug;
        $updatedMovie = $this->movieRepository->updateApi($movie, $data);

        Cache::forget('movies_all');
        return $updatedMovie;
    }

    public function softDeleteApi(int $id): bool
    {
        $movie = $this->movieRepository->findApi($id);
        if (!$movie) {
            return false;
        }

        $deletedMovie = $this->movieRepository->softDelete($movie);

        Cache::forget('movies_all');
        Cache::forget("movies_{$id}");

        return $deletedMovie;
    }

    public function restoreApi(int $id): Movie|null
    {
        $movie = $this->movieRepository->restore($id);
        if (!$movie) {
            return null;
        }

        Cache::forget('movies_all');
        Cache::forget("movies_{$id}");

        return $movie;
    }

    public function forceDeleteApi(int $id): bool
    {
        $deleted = $this->movieRepository->forceDelete($id);
        if (!$deleted) {
            return false;
        }

        Cache::forget('movies_all');
        Cache::forget("movies_{$id}");

        return true;
    }
}
