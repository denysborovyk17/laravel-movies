<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;

class MovieResource extends MovieBaseResource
{
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->title,
            'director_id' => $this->director_id,
            'slug' => $this->slug,
            'description' => $this->description,
            'year' => $this->year,
            'genre' => $this->genre,
            'rating' => $this->rating,
            'status' => $this->status,
            'admin_only' => $this->mergeWhen($request->user()?->role->value === 'admin', [
                'ip' => $this->ip_address,
            ]),
        ];
    }
}
