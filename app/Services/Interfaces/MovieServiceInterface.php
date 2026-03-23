<?php declare(strict_types=1);

namespace App\Services\Interfaces;

use App\DTO\Admin\MovieSearchFilterDto as AdminMovieSearchFilterDto;
use App\Models\Movie;
use Illuminate\Pagination\LengthAwarePaginator;

interface MovieServiceInterface
{
    public function listPublic(?string $search, int $perPage): LengthAwarePaginator;

    public function listAdmin(AdminMovieSearchFilterDto $filter): LengthAwarePaginator;

    public function store(array $data): Movie;

    public function update(Movie $movie, array $data): bool;

    public function delete(Movie $movie): bool;
}
