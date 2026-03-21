<?php declare(strict_types=1);

namespace App\Services\Interfaces;

use App\Models\Movie;
use Illuminate\Database\Eloquent\Collection;

interface ApiMovieServiceInterface
{
    public function getAllApi(): Collection;

    public function getTrashed(): Collection;

    public function getByIdApi(int $movieId): Movie|null;

    public function createApi(array $data): Movie;

    public function updateApi(int $movieId, array $data): ?Movie;

    public function softDeleteApi(int $movieId): bool;

    public function restoreApi(int $movieId): Movie|null;

    public function forceDeleteApi(int $movieId): bool;
}
