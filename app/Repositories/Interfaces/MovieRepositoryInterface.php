<?php

namespace App\Repositories\Interfaces;

use App\Models\Movie;
use Illuminate\Pagination\LengthAwarePaginator;

interface MovieRepositoryInterface
{
    public function listPublic(?string $search = null, int $perPage): LengthAwarePaginator;

    public function listAdmin(?string $search = null, ?string $status, int $perPage): LengthAwarePaginator;

    public function store(array $data): Movie;
}
