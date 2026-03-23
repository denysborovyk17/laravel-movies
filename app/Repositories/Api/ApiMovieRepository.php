<?php declare(strict_types=1);

namespace App\Repositories\Api;

use App\Models\Movie;
use App\Repositories\Interfaces\ApiMovieRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ApiMovieRepository implements ApiMovieRepositoryInterface
{
    public function allApi(): Collection
    {
        return Movie::latest('year')->get();
    }

    public function findApi(int $movieId): Movie|null
    {
        return Movie::find($movieId);
    }

    public function createApi(array $data): Movie
    {
        return Movie::create($data);
    }

    public function updateApi(int $movieId, array $data): Movie|null
    {
        $movie = $this->findApi($movieId);

        if (!$movie) {
            return null;
        }

        $movie->update($data);

        return $movie;
    }

    public function softDelete(int $movieId): bool
    {
        $movie = $this->findApi($movieId);

        if (!$movie) {
            return false;
        }
    
        return $movie->delete();
    }

    public function restore(int $movieId): Movie|null
    {
        $movie = Movie::onlyTrashed()->find($movieId);
        
        if (!$movie) {
            return null;
        }

        $movie->restore();

        return $movie;
    }

    public function forceDelete(int $movieId): bool
    {
        $movie = Movie::find($movieId);
        if (!$movie) {
            return false;
        }

        return $movie->forceDelete();
    }

    public function getTrashed(): Collection
    {
        return Movie::onlyTrashed()->get();
    }
}
