<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\DTO\MovieSearchDto;
use App\Models\Movie;
use App\Services\Interfaces\MovieServiceInterface;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MovieController extends Controller
{
    public function __construct(
        private readonly MovieServiceInterface $movieService
    ) {}

    public function index(Request $request): View
    {
        $search = new MovieSearchDto(
            search: $request->input('search'),
            perPage: config('custom.pagination.public_per_page')
        );
    
        $movies = $this->movieService->listPublic($search);

        return view('movies.index', compact('movies'));
    }

    public function show(Movie $movie): View
    {
        return view('movies.show', compact('movie'));
    }
}
