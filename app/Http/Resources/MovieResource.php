<?php declare(strict_types=1);

namespace App\Http\Resources;

use App\Enums\MovieStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\JsonApi\JsonApiResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'MovieResource',
    properties: [
        new OA\Property(property: 'title', type: 'string', example: 'Movie title'),
        new OA\Property(property: 'director', type: 'string', example: 'Movie director'),
        new OA\Property(property: 'description', type: 'string', example: 'Movie description'),
        new OA\Property(property: 'year', type: 'integer', example: '2026'),
        new OA\Property(property: 'genre', type: 'string', example: 'Movie genre'),
        new OA\Property(property: 'rating', type: 'numeric', example: '10'),
        new OA\Property(property: 'status', type: MovieStatus::class, example: 'published'),
    ],
    type: 'object'
)]
class MovieResource extends JsonApiResource
{
    public function toArray(Request $request): array
    {
        return [
            // 'admin_only' => $this->mergeWhen($request->user()->isAdmin(), [
            //     'ip' => $this->ip_address,
            // ]),
        ];
    }

    public $attributes = [
        'title',
        'director',
        'slug',
        'description',
        'year',
        'genre',
        'rating',
        'status'
    ];

    public $relationships = [
        //
    ];
}
