<?php declare(strict_types=1);

namespace App\Providers;

use App\Repositories\ApiAuthRepository;
use App\Repositories\ApiMovieRepository;
use App\Repositories\Interfaces\{ApiMovieRepositoryInterface, ApiAuthRepositoryInterface};
use App\Repositories\Interfaces\MovieRepositoryInterface;
use App\Repositories\MovieRepository;
use App\Services\{ApiAuthService, ApiMovieService, MovieService};
use App\Services\Interfaces\{ApiAuthServiceInterface, ApiMovieServiceInterface, MovieServiceInterface};
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(MovieRepositoryInterface::class, MovieRepository::class);
        $this->app->bind(ApiMovieServiceInterface::class, ApiMovieService::class);

        $this->app->bind(ApiMovieRepositoryInterface::class, ApiMovieRepository::class);
        $this->app->bind(MovieServiceInterface::class, MovieService::class);
        
        $this->app->bind(ApiAuthRepositoryInterface::class, ApiAuthRepository::class);
        $this->app->bind(ApiAuthServiceInterface::class, ApiAuthService::class);
    }

    public function boot(): void
    {
        Paginator::useBootstrap();
    }
}
