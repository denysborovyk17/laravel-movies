<?php declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use OpenApi\Attributes as OA;

#[OA\Schema(
    title: 'MoviesCollection',
    description: 'Collection of movies',
    properties: [
        new OA\Property(
            property: 'data',
            type: 'array',
            items: new OA\Items(
                ref: '#/components/schemas/MovieResource',
                type: 'object'
            )
        )
    ]
)]
class MoviesCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->map(fn($movie) => [
                'id' => $movie->id,
                'title' => $movie->title,
                'director' => $movie->director,
                'genre' => $movie->genre,
                'rating' => $movie->rating
            ]),
        ];
    }
}
