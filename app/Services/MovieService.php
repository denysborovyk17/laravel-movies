<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Director;
use App\Models\Movie;
use App\Repositories\Interfaces\MovieRepositoryInterface;
use App\Services\Interfaces\MovieServiceInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MovieService implements MovieServiceInterface
{
    public function __construct(
        private readonly MovieRepositoryInterface $movieRepository
    ) {}

    public function listPublic(?string $search, int $perPage = 12): LengthAwarePaginator
    {
        return $this->movieRepository->listPublic($search, $perPage);
    }

    public function listAdmin(?string $search, ?string $status, int $perPage = 12): LengthAwarePaginator
    {
        return $this->movieRepository->listAdmin($search, $status, $perPage);
    }

    public function store(array $data): Movie
    {
        $data = $this->prepareData($data);

        return Movie::create($data);
    }

    public function update(Movie $movie, array $data): bool
    {
        $data = $this->prepareData($data, $movie);

        $filtered = [];
        foreach ($data as $field => $value) {
            if ($field === 'image' || Gate::allows('updateField', [$movie, $field])) {
                $filtered[$field] = $value;
            }
        }

        return $movie->update($filtered);
    }

    public function delete(Movie $movie): bool
    {
        if ($movie->image) {
            Storage::disk('public')->delete($movie->image);
        }

        return $movie->delete();
    }

    protected function prepareData(array $data, ?Movie $movie = null): array
    {
        if (isset($data['director'])) {
            $director = Director::firstOrCreate(['name' => $data['director']]);
            $data['director_id'] = $director->id;
            unset($data['director']);
        }

        if (! isset($movie) || $movie->title !== $data['title']) {
            $slug = Str::slug($data['title']);
            $original = $slug;
            $counter = 1;

            while (Movie::where('slug', $slug)
                ->when($movie, fn($q) => $q->where('id', '!=', $movie->id))
                ->exists()
            ) {
                $slug = $original . '-' . $counter++;
            }

            $data['slug'] = $slug;
        }

        if (! empty($data['remove_image']) && $movie?->image) {
            Storage::disk('public')->delete($movie->image);
            $data['image'] = null;
        }

        if (! empty($data['image_file'])) {
            if ($movie?->image) {
                Storage::disk('public')->delete($movie->image);
            }
            $data['image'] = $data['image_file']->store('admin', 'public');
        }

        if ($movie && ! array_key_exists('image', $data)) {
            $data['image'] = $movie->image;
        }

        return $data;
    }
}
