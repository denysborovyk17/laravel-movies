<?php declare(strict_types=1);

namespace App\Enums;

enum CacheTtl: int
{
    case SHORT = 60;
    case MEDIUM = 300;
    case LONG = 3600;
}
