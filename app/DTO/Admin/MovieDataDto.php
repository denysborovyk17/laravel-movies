<?php declare(strict_types=1);

namespace App\DTO\Admin;

use App\Enums\MovieStatus;
use App\Http\Requests\StoreMovieRequest;
use App\Http\Requests\UpdateMovieRequest;
use Illuminate\Http\UploadedFile;

class MovieDataDto
{
    public function __construct(
        private readonly string $title,
        private readonly string $director,
        private readonly ?UploadedFile $imageFile = null,
        private readonly bool $removeImage,
        private readonly string $description,
        private readonly int $year,
        private readonly string $genre,
        private readonly int $rating,
        private readonly MovieStatus $status
    ) {}

    public static function fromRequest(StoreMovieRequest|UpdateMovieRequest $request): self
    {
        return new self(
            title: $request->validated('title'),
            director: $request->validated('director'),
            imageFile: $request->file('image'),
            removeImage: $request->boolean('remove_image'),
            description: $request->validated('description'),
            year: $request->integer('year'),
            genre: $request->validated('genre'),
            rating: $request->integer('rating'),
            status: MovieStatus::from($request->validated('status'))
        );
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDirector(): string
    {
        return $this->director;
    }

    public function getImageFile(): UploadedFile|null
    {
        return $this->imageFile;
    }

    public function getRemoveImage(): bool
    {
        return $this->removeImage;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function getGenre(): string
    {
        return $this->genre;
    }

    public function getRating(): int
    {
        return $this->rating;
    }

    public function getStatus(): MovieStatus
    {
        return $this->status;
    }
}
