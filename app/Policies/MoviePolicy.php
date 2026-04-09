<?php declare(strict_types=1);

namespace App\Policies;

use App\Models\{User, Movie};

class MoviePolicy
{
    public function update(User $user, Movie $movie): bool
    {
        return $user->isAdmin();
    }

    public function destroy(User $user, Movie $movie): bool
    {
        return $user->isAdmin();
    }

    public function restore(User $user, Movie $movie): bool
    {
        return $user->isAdmin();
    }
}
