<?php

namespace App\Services;

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
        private MovieRepositoryInterface $movieRepository
    ) {}

    public function getAllApi(): Collection
    {
        return Cache::remember('movies_all', 60, function () {
            return $this->movieRepository->allApi();
        });
    }

    public function getByIdApi(int $id): ?Movie
    {
        return Cache::remember("movies_{$id}", 60, function () use ($id) {
            return $this->movieRepository->findApi($id);
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

    public function updateApi(int $id, array $data): ?Movie
    {
        $movie = $this->movieRepository->findApi($id);
        if (!$movie) return null;

        $slug = Str::slug($data['title']);
        $data['slug'] = $slug;
        $updatedMovie = $this->movieRepository->updateApi($movie, $data);

        Cache::forget('movies_all');
        return $updatedMovie;
    }

    public function deleteApi(int $id): bool
    {
        $movie = $this->movieRepository->findApi($id);
        if (!$movie) return false;

        $deletedMovie = $this->movieRepository->deleteApi($movie);
        Cache::forget('movies_all');

        return $deletedMovie;
    }
}
