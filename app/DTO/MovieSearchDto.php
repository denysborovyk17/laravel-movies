<?php declare(strict_types=1);

namespace App\DTO;

class MovieSearchDto
{
    public function __construct(
        private readonly int $perPage,
        private readonly ?string $search = null
    ) {}

    public function hasSearch(): bool
    {
        if (empty($this->search)) {
            return false;
        }

        return true;
    }

    public function getSearch(): string|null
    {
        return $this->search;
    }

    public function getPerPage(): int
    {
        return $this->perPage;
    }
}
