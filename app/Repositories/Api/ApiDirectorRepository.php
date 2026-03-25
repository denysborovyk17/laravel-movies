<?php declare(strict_types=1);

namespace App\Repositories\Api;

use App\Models\Director;
use App\Repositories\Interfaces\Api\ApiDirectorRepositoryInterface;
use Exception;

class ApiDirectorRepository implements ApiDirectorRepositoryInterface
{
    public function findOrCreate(string $name): Director
    {    
        $trimmedName = trim($name);

        return Director::firstOrCreate(['name' => $trimmedName]);
    }
}
