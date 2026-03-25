<?php declare(strict_types=1);

namespace App\Services\Interfaces;

use App\DTO\Admin\MovieDataDto;
use App\DTO\Admin\MovieSearchFilterDto as AdminMovieSearchFilterDto;
use App\DTO\MovieSearchDto;
use App\Models\Movie;
use Illuminate\Pagination\LengthAwarePaginator;

interface MovieServiceInterface
{
    public function listPublic(MovieSearchDto $search): LengthAwarePaginator;

    public function listAdmin(AdminMovieSearchFilterDto $filter): LengthAwarePaginator;

    public function store(MovieDataDto $movieDTO): Movie;

    public function update(MovieDataDto $movieDTO, Movie $movie): bool|null;

    public function delete(Movie $movie): bool;

    public function buildData(MovieDataDto $movieDTO, ?Movie $movie): array;

    public function generateSlug(string $title, ?Movie $movie);

    public function handleImage(MovieDataDto $movieDTO, ?Movie $movie);
}
