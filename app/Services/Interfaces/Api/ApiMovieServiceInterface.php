<?php declare(strict_types=1);

namespace App\Services\Interfaces\Api;

use App\DTO\Admin\MovieDataDto;
use App\Models\Movie;
use Illuminate\Database\Eloquent\Collection;

interface ApiMovieServiceInterface
{
    public function getAllApi(): Collection;

    public function getTrashed(): Collection;

    public function getByIdApi(int $movieId): Movie|null;

    public function createApi(MovieDataDto $movieDTO): Movie;

    public function updateApi(MovieDataDto $movieDTO, int $movieId): Movie;

    public function softDeleteApi(int $movieId): void;

    public function restoreApi(int $movieId): Movie|null;

    public function forceDeleteApi(int $movieId): bool;

    public function buildDataApi(MovieDataDto $movieDTO, ?Movie $movie): array;
}
