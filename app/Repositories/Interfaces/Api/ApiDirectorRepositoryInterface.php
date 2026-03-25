<?php declare(strict_types=1);

namespace App\Repositories\Interfaces\Api;

use App\Models\Director;

interface ApiDirectorRepositoryInterface
{
    public function findOrCreate(string $name): Director;
}
