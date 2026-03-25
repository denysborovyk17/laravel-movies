<?php declare(strict_types=1);

namespace App\Repositories\Interfaces;

use App\DTO\Auth\RegisterDto;
use App\Models\User;

interface AuthRepositoryInterface
{
    public function register(RegisterDto $userDTO): User;
}
