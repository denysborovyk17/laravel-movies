<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMovieRequest;
use App\Http\Requests\UpdateMovieRequest;
use App\Models\Movie;
use App\Services\MovieService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MovieController extends Controller
{
    use AuthorizesRequests;

    public function __construct(MovieService $movieService) {
        $this->movieService = $movieService;
    }


    public function index(Request $request)
    {
        $movies = $this->movieService->listAdmin(
            $request->get('search'),
            $request->get('status')
        );

        return view('admin.movies.index', compact('movies'));
    }

    public function create(): View
    {
        $this->authorize('create', Movie::class);

        return view('admin.movies.create');
    }

    public function store(StoreMovieRequest $request): RedirectResponse
    {
        $this->authorize('create', Movie::class);

        $this->movieService->store($request->validated());

        return redirect()->route('admin.movies.index')->with('success', 'Movie created successfully');
    }

    public function edit(Movie $movie)
    {
        $this->authorize('update', $movie);

        return view('admin.movies.edit', compact('movie'));
    }

    public function update(UpdateMovieRequest $request, Movie $movie)
    {
        $this->authorize('update', $movie);

        $this->movieService->update($movie, $request->validated());

        return redirect()->route('admin.movies.index')->with('success', 'Movie updated successfully');
    }

    public function destroy(Movie $movie)
    {
        $this->authorize('delete', $movie);

        $this->movieService->delete($movie);

        return redirect()->route('admin.movies.index')->with('success', 'Movie deleted successfully');
    }
}
