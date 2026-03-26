<?php declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\JsonApi\JsonApiResource;

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
