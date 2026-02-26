<?php

namespace App\Repositories\Interfaces;

use App\Models\Movie;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface MovieRepositoryInterface
{
    public function listPublic(?string $search, int $perPage): LengthAwarePaginator;

    public function listAdmin(?string $search = null, ?string $status, int $perPage): LengthAwarePaginator;

    public function allApi(): Collection;

    public function findApi(int $id): ?Movie;

    public function createApi(array $data): Movie;

    public function updateApi(Movie $movie, array $data): Movie;

    public function softDelete(Movie $movie): bool;

    public function restore(int $id): ?Movie;

    public function forceDelete(int $id): bool;

    public function getTrashed(): Collection;
}
