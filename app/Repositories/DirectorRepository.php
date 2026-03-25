<?php declare(strict_types=1);

namespace App\Repositories;

use App\Models\Director;
use App\Repositories\Interfaces\DirectorRepositoryInterface;

class DirectorRepository implements DirectorRepositoryInterface
{
    public function store(string $name): Director
    {
        return Director::firstOrCreate(['name' => $name]);
    }
}
