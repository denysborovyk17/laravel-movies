<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MovieBaseResource extends JsonResource
{
    public function with(Request $request)
    {
        return [
            'success' => true,
        ];
    }
}
