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

    private function prepareMovieData(Request $request): array
    {
        $validated = $request->validated();

        return array_merge($validated, [
            'image_file' => $request->file('image'),
            'remove_image' => $request->boolean('remove_image')
        ]);
    }

    public function index(Request $request): View
    {
        $movies = $this->movieService->listAdmin(
            $request->get('search'),
            $request->get('status')
        );

        $movies->appends($request->only(['search', 'status']));

        return view('admin.movies.index', compact('movies'));
    }

    public function create(): View
    {
        $this->authorize('create', Movie::class);

        return view('admin.movies.create');
    }

    public function store(StoreMovieRequest $request): RedirectResponse
    {
        $data = $this->prepareMovieData($request);

        $this->authorize('create', Movie::class);

        $this->movieService->store($data);

        return redirect()->route('admin.movies.index')->with('success', 'Movie created successfully');
    }

    public function edit(Movie $movie): View
    {
        $this->authorize('update', $movie);

        return view('admin.movies.edit', compact('movie'));
    }

    public function update(UpdateMovieRequest $request, Movie $movie): RedirectResponse
    {
        $data = $this->prepareMovieData($request);

        $this->authorize('update', $movie);

        $this->movieService->update($movie, $data);

        return redirect()->route('admin.movies.index')->with('success', 'Movie updated successfully');
    }

    public function destroy(Movie $movie): RedirectResponse
    {
        $this->authorize('delete', $movie);

        $this->movieService->delete($movie);

        return redirect()->route('admin.movies.index')->with('success', 'Movie deleted successfully');
    }
}
