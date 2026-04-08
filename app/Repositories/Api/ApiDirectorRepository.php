<?php declare(strict_types=1);

namespace App\Repositories\Api;

use App\Models\Director;
use App\Repositories\Interfaces\Api\ApiDirectorRepositoryInterface;

class ApiDirectorRepository implements ApiDirectorRepositoryInterface
{
    public function findOrCreate(string $name): Director
    {
        return Director::firstOrCreate(['name' => $name]);
    }
}
