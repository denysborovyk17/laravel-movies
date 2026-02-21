<?php

namespace App\Policies;

use App\Models\Movie;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MoviePolicy
{
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, Movie $movie): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Movie $movie): bool
    {
        return $user->isAdmin();
    }
}
