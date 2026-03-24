<?php declare(strict_types=1);

namespace App\Services;

use App\DTO\Admin\MovieDataDto;
use App\DTO\Admin\MovieSearchFilterDto as AdminMovieSearchFilterDto;
use App\Models\{Movie};
use App\Repositories\Interfaces\DirectorRepositoryInterface;
use App\Repositories\Interfaces\MovieRepositoryInterface;
use App\Services\Interfaces\MovieServiceInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MovieService implements MovieServiceInterface
{
    public function __construct(
        private readonly MovieRepositoryInterface $movieRepository,
        private readonly DirectorRepositoryInterface $directorRepository
    ) {}

    public function listPublic(?string $search, int $perPage = 12): LengthAwarePaginator
    {
        return $this->movieRepository->listPublic($search, $perPage);
    }

    public function listAdmin(AdminMovieSearchFilterDto $filter): LengthAwarePaginator
    {
        return $this->movieRepository->listAdmin($filter);
    }

    public function store(MovieDataDto $movieDTO): Movie
    {
        $data = $this->buildData($movieDTO, null);

        return $this->movieRepository->store($data);
    }

    public function update(MovieDataDto $movieDTO, Movie $movie): bool|null
    {    
        $data = $this->buildData($movieDTO, $movie);

        return $movie->update($data);
    }

    public function delete(Movie $movie): bool
    {
        if ($movie->image) {
            Storage::disk('public')->delete($movie->image);
        }

        return $movie->delete();
    }

    public function buildData(MovieDataDto $movieDTO, ?Movie $movie): array
    {
        $director = $movieDTO->getDirector();
    
        return [
            'title' => $movieDTO->getTitle(),
            'director_id' => $this->directorRepository->store($director)->id,
            'description' => $movieDTO->getDescription(),
            'year' => $movieDTO->getYear(),
            'genre' => $movieDTO->getGenre(),
            'rating' => $movieDTO->getRating(),
            'status' => $movieDTO->getStatus(),

            'slug' => $this->generateSlug($movieDTO->getTitle(), $movie),
            'image' => $this->handleImage($movieDTO, $movie)
        ];
    }

    public function generateSlug(string $title, ?Movie $movie)
    {
        if (!$movie || $title !== $movie->title) {
            $slug = Str::slug($title);
            $original = $slug;
            $counter = 1;

            while (Movie::where('slug', $slug)
                ->when($movie, fn($q) => $q->where('id', '!=', $movie))
                ->exists()    
            ) {
                $slug = $original . '-' . $counter;
            }

            return $slug;   
        }
        return $movie?->slug;
    }

    public function handleImage(MovieDataDto $movieDTO, ?Movie $movie)
    {
        if (!empty($movieDTO->getImageFile())) {
            if ($movie?->image) {
                Storage::disk('public')->delete($movie->image);
            }

            return $movieDTO->getImageFile()->store('admin', 'public');
        }
        
        if (!empty($movieDTO->getRemoveImage()) && $movie?->image) {
            Storage::disk('public')->delete($movie->image);
            return null;
        }

        return $movie?->image;
    }
}
