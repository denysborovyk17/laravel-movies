<?php declare(strict_types=1);

namespace App\Repositories;

use App\DTO\Admin\MovieSearchFilterDto;
use App\DTO\MovieSearchDto;
use App\Enums\MovieStatus;
use App\Models\Movie;
use App\Repositories\Interfaces\MovieRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class MovieRepository implements MovieRepositoryInterface
{
    public function listPublic(MovieSearchDto $search): LengthAwarePaginator
    {
        return Movie::with('director')
            ->where('status', MovieStatus::PUBLISHED->value)
            ->when(
                $search->hasSearch(),
                fn($q) => $q->where(
                    fn($q) => $q->where('title', 'like', "%{$search->getSearch()}%")
                        ->orWhere('description', 'like', "%{$search->getSearch()}%")
                )
            )
            ->latest('year')
            ->paginate($search->getPerPage());
    }

    public function listAdmin(MovieSearchFilterDto $filter): LengthAwarePaginator
    {
        return Movie::with('director')
            ->when($filter->hasStatus(), fn($q) => $q->where('status', $filter->getStatus()->value))
            ->when(
                $filter->hasSearch(),
                fn($q) => $q->where(
                    fn($q) => $q->where('title', 'like', "%{$filter->getSearch()}%")
                        ->orWhere('description', 'like', "%{$filter->getSearch()}%")
                )
            )
            ->latest('year')
            ->paginate($filter->getPerPage());
    }

    public function getById(int $movieId): Movie
    {
        return Movie::find($movieId);
    }

    public function store(array $data): Movie
    {
        return Movie::create($data);
    }
}
