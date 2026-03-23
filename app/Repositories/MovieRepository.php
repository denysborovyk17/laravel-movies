<?php declare(strict_types=1);

namespace App\Repositories;

use App\DTO\Admin\MovieSearchFilterDto as AdminMovieSearchFilterDto;
use App\Models\Movie;
use App\Repositories\Interfaces\MovieRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class MovieRepository implements MovieRepositoryInterface
{
    public function listPublic(?string $search = null, int $perPage = 12): LengthAwarePaginator
    {
        return Movie::with('director')
            ->where('status', 'published')
            ->when(
                $search,
                fn($q) => $q->where(
                    fn($q) => $q->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                )
            )
            ->latest('year')
            ->paginate($perPage);
    }

    public function listAdmin(AdminMovieSearchFilterDto $filter): LengthAwarePaginator
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

    public function store(array $data): Movie
    {
        return Movie::create($data);
    }
}
