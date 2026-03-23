<?php

namespace App\Repositories\Interfaces;

use App\DTO\Admin\MovieSearchFilterDto as AdminMovieSearchFilterDto;
use App\Models\Movie;
use Illuminate\Pagination\LengthAwarePaginator;

interface MovieRepositoryInterface
{
    public function listPublic(?string $search = null, int $perPage): LengthAwarePaginator;

    public function listAdmin(AdminMovieSearchFilterDto $filter): LengthAwarePaginator;

    public function store(array $data): Movie;
}
