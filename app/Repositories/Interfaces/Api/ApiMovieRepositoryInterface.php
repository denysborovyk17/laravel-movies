<?php declare(strict_types=1);

namespace App\Repositories\Interfaces;

use App\Models\Movie;
use Illuminate\Database\Eloquent\Collection;

interface ApiMovieRepositoryInterface
{
    public function allApi(): Collection;

    public function findApi(int $movieId): Movie|null;

    public function createApi(array $data): Movie;

    public function updateApi(int $movieId, array $data): Movie|null;

    public function softDelete(int $movieId): bool;

    public function restore(int $movieId): Movie|null;

    public function forceDelete(int $movieId): bool;

    public function getTrashed(): Collection;
}
