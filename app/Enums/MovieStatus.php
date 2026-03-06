<?php

declare(strict_types=1);

namespace App\Enums;

enum MovieStatus: string
{
    case DRAFT = 'draft';
    case ARCHIVED = 'archived';
    case PUBLISHED = 'published';
}
