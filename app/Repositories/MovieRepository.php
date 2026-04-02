<?php declare(strict_types=1);

namespace App\Repositories;

use App\DTO\Admin\MovieDataDto;
use App\DTO\Admin\MovieSearchFilterDto;
use App\DTO\MovieSearchDto;
use App\Models\Movie;
use App\Repositories\Interfaces\MovieRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class MovieRepository implements MovieRepositoryInterface
{
    public function listPublic(MovieSearchDto $search): LengthAwarePaginator
    {
        return Movie::with('director')
            ->published()
            ->when($search->hasSearch(),
                fn($q) => $this->applySearch($q, $search->getSearch()))
            ->latest('year')
            ->paginate($search->getPerPage());
    }

    public function listAdmin(MovieSearchFilterDto $filter): LengthAwarePaginator
    {
        return Movie::with('director')
            ->when($filter->hasStatus(), fn($q) => $q->where('status', $filter->getStatus()->value))
            ->when($filter->hasSearch(),
                fn($q) => $this->applySearch($q, $filter->getSearch()))
            ->latest('year')
            ->paginate($filter->getPerPage());
    }

    private function applySearch(Builder $query, string $search): Builder
    {
        return $query->where(
            fn($q) => $q->where('title', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")
        );
    }

    public function getById(int $movieId): Movie|null
    {
        return Movie::find($movieId);
    }

    public function store(array $data): Movie
    {
        return Movie::create($data);
    }
}
