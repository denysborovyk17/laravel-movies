<?php

namespace App\Policies;

use App\Models\Movie;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MoviePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Movie $movie): bool
    {
        return true;
    }

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

    public function restore(User $user, Movie $movie): bool
    {
        return $user->isAdmin();
    }

    public function forceDelete(User $user, Movie $movie): bool
    {
        return $user->isAdmin();
    }

    public function updateField(User $user, Movie $movie,  string $field): bool
    {
        return match ($field) {
            'director_id', 'title', 'image', 'slug', 'description', 'year', 'genre', 'rating', 'status' => $user->isAdmin(),
            default => false
        };
    }
}
