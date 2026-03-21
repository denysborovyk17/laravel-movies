<?php declare(strict_types=1);

namespace App\Providers;

use App\Repositories\Interfaces\MovieRepositoryInterface;
use App\Repositories\MovieRepository;
use App\Services\{ApiMovieService, MovieService};
use App\Services\Interfaces\{ApiMovieServiceInterface, MovieServiceInterface};
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(MovieRepositoryInterface::class, MovieRepository::class);

        $this->app->bind(MovieServiceInterface::class, MovieService::class);
        $this->app->bind(ApiMovieServiceInterface::class, ApiMovieService::class);
    }

    public function boot(): void
    {
        Paginator::useBootstrap();
    }
}
