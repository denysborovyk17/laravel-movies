<?php declare(strict_types=1);

namespace App\Services;

use App\DTO\Admin\MovieSearchFilterDto as AdminMovieSearchFilterDto;
use App\Models\{Movie, Director};
use App\Repositories\Interfaces\MovieRepositoryInterface;
use App\Services\Interfaces\MovieServiceInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MovieService implements MovieServiceInterface
{
    public function __construct(
        private readonly MovieRepositoryInterface $movieRepositoryInterface
    ) {}

    public function listPublic(?string $search, int $perPage = 12): LengthAwarePaginator
    {
        return $this->movieRepositoryInterface->listPublic($search, $perPage);
    }

    public function listAdmin(AdminMovieSearchFilterDto $filter): LengthAwarePaginator
    {
        return $this->movieRepositoryInterface->listAdmin($filter);
    }

    public function store(array $data): Movie
    {
        $data = $this->prepareData($data);

        return $this->movieRepositoryInterface->store($data);
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

        return $movie->update($data, $filtered);
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
