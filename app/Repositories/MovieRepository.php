<?php

namespace App\Repositories;

use App\Models\Movie;
use App\Repositories\Interfaces\MovieRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class MovieRepository implements MovieRepositoryInterface
{
    public function __construct() {}

    public function listPublic(?string $search = null, int $perPage = 12): LengthAwarePaginator
    {
        return Movie::with('director')
            ->where('status', 'published')
            ->when($search, fn($q) =>
                $q->where(fn($q) =>
                    $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                )
            )
            ->latest('year')
            ->paginate($perPage);
    }

    public function listAdmin(?string $search = null, ?string $status = null, int $perPage = 12): LengthAwarePaginator
    {
        return Movie::with('director')
            ->when($status, fn($q) => $q->where('status', $status))
            ->when($search, fn($q) =>
                $q->where(fn($q) =>
                    $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                )
            )
            ->latest('year')
            ->paginate($perPage);
    }

    public function allApi(): Collection
    {
        return Movie::latest('year')->get();
    }

    public function findApi(int $id): ?Movie
    {
        return Movie::find($id);
    }

    public function createApi(array $data): Movie
    {
        return Movie::create($data);
    }

    public function updateApi(Movie $movie, array $data): Movie
    {
        $movie->update($data);
        return $movie;
    }

    public function deleteApi(Movie $movie): bool
    {
        return $movie->delete();
    }
}
