<?php declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Str;

class SlugService
{
    public function __construct(
        //
    ) {}

    public function generateUnique(string $title, string $modelClass, ?int $movieId = null): string
    {
        $slug = Str::slug($title);
        $original = $slug;
        $counter = 1;

        while ($modelClass::where('slug', $slug)
            ->when($movieId, fn($q) => $q->where('id', '!=', $movieId))
            ->exists()
        ) {
            $slug = $original . '-' . $counter++;
        }

        return $slug;
    }
}
