<?php

namespace App\Services;

use App\Models\Director;
use App\Models\Movie;
use App\Repositories\Interfaces\MovieRepositoryInterface;
use App\Services\Interfaces\ApiMovieServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class ApiMovieService implements ApiMovieServiceInterface
{
    public function __construct(
        private MovieRepositoryInterface $movieRepository
    ) {}

    public function getAllApi(): Collection
    {
        return $this->movieRepository->allApi();
    }

    public function getByIdApi(int $id): ?Movie
    {
        return $this->movieRepository->findApi($id);
    }

    public function createApi(array $data): Movie
    {
        $director = Director::firstOrCreate(['name' => $data['director']]);

        $data['director_id'] = $director->id;
        unset($data['director']);
    
        $slug = Str::slug($data['title']);
        $data['slug'] = $slug;
        return $this->movieRepository->createApi($data);
    }

    public function updateApi(int $id, array $data): ?Movie
    {
        $movie = $this->movieRepository->findApi($id);
        if (!$movie) return null;

        $slug = Str::slug($data['title']);
        $data['slug'] = $slug;
        return $this->movieRepository->updateApi($movie, $data);
    }

    public function deleteApi(int $id): bool
    {
        $movie = $this->movieRepository->findApi($id);
        if (!$movie) return false;

        return $this->movieRepository->deleteApi($movie);
    }
}
