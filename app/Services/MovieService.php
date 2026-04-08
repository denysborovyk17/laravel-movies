<?php declare(strict_types=1);

namespace App\Services;

use App\DTO\Admin\{MovieDataDto, MovieSearchFilterDto};
use App\DTO\MovieSearchDto;
use App\Exceptions\MovieNotFoundException;
use App\Models\{Movie};
use App\Repositories\Interfaces\{MovieRepositoryInterface, DirectorRepositoryInterface};
use App\Services\Interfaces\MovieServiceInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class MovieService implements MovieServiceInterface
{
    public function __construct(
        private readonly MovieRepositoryInterface $movieRepository,
        private readonly DirectorRepositoryInterface $directorRepository,
        private readonly SlugService $slugService,
        private readonly FileService $fileService,
    ) {}

    public function listPublic(MovieSearchDto $search): LengthAwarePaginator
    {
        return $this->movieRepository->listPublic($search);
    }

    public function listAdmin(MovieSearchFilterDto $filter): LengthAwarePaginator
    {
        return $this->movieRepository->listAdmin($filter);
    }

    /**
     * @throws MovieNotFoundException
     */
    public function getById(int $movieId): Movie
    {
        $movie = $this->movieRepository->getById($movieId);

        if (!$movie) {
            throw new MovieNotFoundException($movieId);
        }

        return $movie;
    }

    public function store(MovieDataDto $movieDTO): Movie
    {
        $data = $this->buildData($movieDTO, null);

        return $this->movieRepository->store($data);
    }

    public function update(MovieDataDto $movieDTO, int $movieId): bool|null
    {
        $movie = $this->movieRepository->getById($movieId);

        $data = $this->buildData($movieDTO, $movie);

        return $movie->update($data);
    }

    public function delete(int $movieId): bool
    {
        $movie = $this->movieRepository->getById($movieId);

        return $movie->delete();
    }

    public function buildData(MovieDataDto $movieDTO, ?Movie $movie): array
    {
        $director = $this->directorRepository->findOrCreate($movieDTO->getDirector());
        $slug = $this->slugService->generateUnique($movieDTO->getTitle(), Movie::class, $movie?->id);

        $uploadedPath = $movie?->image;
        if ($movieDTO->getImageFile()) {
            $this->fileService->delete($movie?->image);
            $uploadedPath = $this->fileService->upload($movieDTO->getImageFile(), 'admin');
        } elseif ($movieDTO->getRemoveImage()) {
            $this->fileService->delete($movie?->image);
            $uploadedPath = null;
        }

        return [
            'title' => $movieDTO->getTitle(),
            'director_id' => $director->id,
            'description' => $movieDTO->getDescription(),
            'year' => $movieDTO->getYear(),
            'genre' => $movieDTO->getGenre(),
            'rating' => $movieDTO->getRating(),
            'status' => $movieDTO->getStatus(),
            'slug' => $slug,
            'image' => $uploadedPath,
        ];
    }
}
