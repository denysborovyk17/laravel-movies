<?php

namespace App\Services;

use App\Models\Director;
use App\Models\Movie;
use App\Repositories\Interfaces\MovieRepositoryInterface;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Gate;

class MovieService
{
    public function listPublic(?string $search = null, int $perPage = 12)
    {
        return Movie::where('status', 'published')
            ->when($search, fn($q) =>
                $q->where('title', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")
            )
            ->paginate($perPage);
    }

    public function listAdmin(?string $search = null, ?string $status = null)
    {
        return Movie::query()
            ->when($status, fn($q) => $q->where('status', $status))
            ->when($search, fn($q) => $q->where(fn($q) =>
                $q->where('title', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")
                )
            )
            ->latest('year')
            ->get();
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
            if (Gate::allows('updateField', [$movie, $field])) {
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
        $director = Director::firstOrCreate(['name' => $data['director']]);
        $data['director_id'] = $director->id;
        unset($data['director']);

        if (!isset($movie) || $movie->title !== $data['title']) {
            $slug = Str::slug($data['title']);
            $original = $slug;
            $counter = 1;

            while (Movie::where('slug', $slug)
                ->when($movie, fn($q) => $q->where('id', '!=', $movie->id))
                ->exists()) {
                $slug = $original . '-' . $counter++;
            }

            $data['slug'] = $slug;
        }

        if (request()->boolean('remove_image') && $movie?->image) {
            Storage::disk('public')->delete($movie->image);
            $data['image'] = null;
        }

        if (request()->hasFile('image')) {
            if ($movie?->image) {
                Storage::disk('public')->delete($movie->image);
            }
            $data['image'] = request()->file('image')->store('admin', 'public');
        }

        if ($movie && !isset($data['image'])) {
            $data['image'] = $movie->image;
        }

        return $data;
    }

}
