<?php declare(strict_types=1);

namespace App\DTO\Admin;

use App\Enums\MovieStatus;

class MovieSearchFilterDto
{
    public function __construct(
        private readonly ?string $search,
        private readonly ?MovieStatus $status,
        private readonly int $perPage
    ) {}

    public function getSearch(): string|null
    {
        return $this->search;
    }

    public function getStatus(): MovieStatus|null
    {
        return $this->status;
    }

    public function getPerPage(): int
    {
        return $this->perPage;
    }

    public function hasSearch(): bool
    {
        return !empty($this->search);
    }

    public function hasStatus(): bool
    {
        return $this->status !== null;
    }
}
