<?php declare(strict_types=1);

namespace App\Repositories\Interfaces;

use App\Models\Director;

interface DirectorRepositoryInterface
{
    public function store(string $name): Director;
}
