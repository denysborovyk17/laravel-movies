<?php declare(strict_types=1);

namespace App\Providers;

use App\Repositories\Api\{ApiAuthRepository, ApiDirectorRepository, ApiMovieRepository};
use App\Repositories\{AuthRepository, DirectorRepository, MovieRepository};
use App\Repositories\Interfaces\Api\{ApiAuthRepositoryInterface, ApiDirectorRepositoryInterface, ApiMovieRepositoryInterface};
use App\Repositories\Interfaces\{AuthRepositoryInterface, DirectorRepositoryInterface, MovieRepositoryInterface};
use App\Services\Api\{ApiAuthService, ApiMovieService};
use App\Services\{AuthService, MovieService};
use App\Services\Interfaces\Api\{ApiAuthServiceInterface, ApiMovieServiceInterface};
use App\Services\Interfaces\{AuthServiceInterface, MovieServiceInterface};
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ApiMovieRepositoryInterface::class, ApiMovieRepository::class);
        $this->app->bind(ApiMovieServiceInterface::class, ApiMovieService::class);

        $this->app->bind(MovieRepositoryInterface::class, MovieRepository::class);
        $this->app->bind(MovieServiceInterface::class, MovieService::class);

        $this->app->bind(ApiAuthRepositoryInterface::class, ApiAuthRepository::class);
        $this->app->bind(ApiAuthServiceInterface::class, ApiAuthService::class);
        
        $this->app->bind(AuthRepositoryInterface::class, AuthRepository::class);
        $this->app->bind(AuthServiceInterface::class, AuthService::class);

        $this->app->bind(ApiDirectorRepositoryInterface::class, ApiDirectorRepository::class);
        $this->app->bind(DirectorRepositoryInterface::class, DirectorRepository::class);
    }

    public function boot(): void
    {
        Paginator::useBootstrap();
    }
}
