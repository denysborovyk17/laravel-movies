<?php declare(strict_types=1);

namespace App\Repositories\Interfaces;

use App\DTO\Admin\MovieDataDto;
use App\DTO\Admin\MovieSearchFilterDto;
use App\DTO\MovieSearchDto;
use App\Models\Movie;
use Illuminate\Pagination\LengthAwarePaginator;

interface MovieRepositoryInterface
{
    public function listPublic(MovieSearchDto $search): LengthAwarePaginator;

    public function listAdmin(MovieSearchFilterDto $filter): LengthAwarePaginator;

    public function getById(int $movieId): Movie|null;

    public function store(MovieDataDto $dto): Movie;
}
