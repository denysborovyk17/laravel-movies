<?php

namespace App\Enums;

enum MovieStatus: string
{
    case DRAFT = 'draft';
    case ARCHIVED = 'archived';
    case PUBLISHED = 'published';
}
