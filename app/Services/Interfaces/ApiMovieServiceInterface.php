<?php

namespace App\Services\Interfaces;

use App\Models\Movie;
use Illuminate\Database\Eloquent\Collection;

interface ApiMovieServiceInterface
{
    public function getAllApi(): Collection;

    public function getByIdApi(int $id): ?Movie;

    public function createApi(array $data): Movie;

    public function updateApi(int $id, array $data): ?Movie;

    public function deleteApi(int $id): bool;
}
