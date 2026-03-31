<?php declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\DTO\Admin\MovieDataDto;
use App\DTO\Admin\MovieSearchFilterDto;
use App\Enums\MovieStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\{StoreMovieRequest, UpdateMovieRequest};
use App\Models\Movie;
use App\Services\Interfaces\MovieServiceInterface;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MovieController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        private readonly MovieServiceInterface $movieService
    ) {}

    public function index(Request $request): View
    {
        $filter = new MovieSearchFilterDto(
            search: $request->input('search'),
            status: $request->input('status') ? MovieStatus::from($request->input('status')) : null,
            perPage: config('custom.pagination.admin_per_page')
        );
    
        $movies = $this->movieService->listAdmin($filter);

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

        $movieDTO = MovieDataDto::fromRequest($request);

        $this->movieService->store($movieDTO);

        return redirect()->route('admin.movies.index')->with('success', 'Movie created successfully');
    }

    public function edit(int $movieId): View
    {
        $movie = $this->movieService->getById($movieId);
    
        $this->authorize('update', $movie);

        return view('admin.movies.edit', compact('movie'));
    }

    public function update(UpdateMovieRequest $request, int $movieId): RedirectResponse
    {
        $movie = $this->movieService->getById($movieId);
    
        $this->authorize('update', $movie);

        $movieDTO = MovieDataDto::fromRequest($request);

        $this->movieService->update($movieDTO, $movieId);

        return redirect()->route('admin.movies.index')->with('success', 'Movie updated successfully');
    }

    public function destroy(int $movieId): RedirectResponse
    {
        $movie = $this->movieService->getById($movieId);
    
        $this->authorize('delete', $movie);

        $this->movieService->delete($movie);

        return redirect()->route('admin.movies.index')->with('success', 'Movie deleted successfully');
    }
}
