<?php declare(strict_types=1);

namespace App\Repositories;

use App\Models\Director;
use App\Repositories\Interfaces\DirectorRepositoryInterface;

class DirectorRepository implements DirectorRepositoryInterface
{
    public function findOrCreate(string $name): Director
    {
        $trimmedName = trim($name);
    
        return Director::firstOrCreate(['name' => $trimmedName]);
    }
}
