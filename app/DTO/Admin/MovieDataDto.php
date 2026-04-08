<?php declare(strict_types=1);

namespace App\DTO\Admin;

use App\Enums\MovieStatus;
use Illuminate\Http\UploadedFile;

class MovieDataDto
{
    public function __construct(
        private readonly string $title,
        private readonly string $director,
        private readonly string $description,
        private readonly int $year,
        private readonly string $genre,
        private readonly float $rating,
        private readonly MovieStatus $status,
        private readonly ?UploadedFile $imageFile = null,
        private readonly bool $removeImage = false
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            title: (string) $data['title'],
            director: (string) $data['director'],
            description: (string) $data['description'],
            year: (int) $data['year'],
            genre: (string) $data['genre'],
            rating: (float) $data['rating'],
            status: $data['status'],
            imageFile: $data['imageFile'],
            removeImage: (bool) $data['removeImage']
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

    public function getRating(): float
    {
        return $this->rating;
    }

    public function getStatus(): MovieStatus
    {
        return $this->status;
    }
}
