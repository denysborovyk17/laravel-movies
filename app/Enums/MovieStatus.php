<?php declare(strict_types=1);

namespace App\Enums;

enum MovieStatus: string
{
    case DRAFT = 'draft';
    case ARCHIVED = 'archived';
    case PUBLISHED = 'published';

    public function isDraft(): bool
    {
        return $this === self::DRAFT;
    }

    public function isArchived(): bool
    {
        return $this === self::ARCHIVED;
    }

    public function isPublished(): bool
    {
        return $this === self::PUBLISHED;
    }
}
