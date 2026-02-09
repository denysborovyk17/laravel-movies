<?php

namespace Database\Seeders;

use App\Models\Director;
use App\Models\Movie;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MovieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $nolan = Director::where('name', 'Christopher Nolan')->first();
        $spielberg = Director::where('name', 'Steven Spielberg')->first();

        Movie::create([
            'title' => 'Inception',
            'slug' => 'inception',
            'description' => 'Dream within a dream...',
            'year' => 2010,
            'genre' => 'Sci-Fi',
            'rating' => 8.8,
            'director_id' => $nolan->id,
        ]);

        Movie::create([
            'title' => 'Jurassic Park',
            'slug' => 'jurassic-park',
            'description' => 'Dinosaurs come to life!',
            'year' => 1993,
            'genre' => 'Adventure',
            'rating' => 8.1,
            'director_id' => $spielberg->id,
        ]);
    }
}
