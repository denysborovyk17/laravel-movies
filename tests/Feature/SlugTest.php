<?php

namespace Tests\Feature;

use App\Models\Movie;
use App\Services\SlugService;
use Tests\TestCase;

class SlugTest extends TestCase
{
    public function test_slug_is_generated_correct(): void
    {
        $service = new SlugService();

        $slug = $service->generateUnique('Game Of Thrones', Movie::class);

        $this->assertEquals('game-of-thrones', $slug);
    }
}
