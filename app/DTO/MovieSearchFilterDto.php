<?php declare(strict_types=1);

namespace App\DTO;

use App\Enums\MovieStatus;

class MovieSearchFilterDto
{
    public function __construct(
        private ?string $search = null,
        private ?MovieStatus $status = null,
        private int $perPage = 12
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
        if (empty($this->search)) {
            return false;
        }

        return true;
    }

    public function hasStatus(): bool
    {
        if (!$this->status) {
            return false;
        }

        return true;
    }
}
