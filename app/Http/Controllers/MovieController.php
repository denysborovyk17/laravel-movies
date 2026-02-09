<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Services\MovieService;
use Illuminate\Http\Request;


class MovieController extends Controller
{
    public function __construct(
        private MovieService $movieService
    ) {}


    public function index(Request $request)
    {
        $movies = $this->movieService->listPublic($request->get('search'), 12);
        $movies->load('director');

        return view('movies.index', compact('movies'));
    }

    public function show(Movie $movie)
    {
        return view('movies.show', compact('movie'));
    }
}
