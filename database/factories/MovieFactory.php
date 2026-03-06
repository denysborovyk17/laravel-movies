<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Movie>
 */
class MovieFactory extends Factory
{
    public function definition(): array
    {
        $title = $this->faker->sentence(mt_rand(5, 8));

        $slugBase = Str::slug($title);
        $slug = $slugBase.'-'.$this->faker->unique()->numberBetween(1000, 999999);

        return [
            'title' => $title,
            'slug' => $slug,
            'description' => $this->faker->paragraph(mt_rand(2, 4)),
            'year' => $this->faker->year(2050),
            'genre' => $this->faker->dateTimeBetween('-30days', 'now'),
            'rating' => $this->faker->numberBetween(1, 10),
        ];
    }
}
