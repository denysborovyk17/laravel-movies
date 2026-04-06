<?php declare(strict_types=1);

namespace App\Providers;

use App\Repositories\Api\{ApiAuthRepository, ApiDirectorRepository, ApiMovieRepository};
use App\Repositories\{AuthRepository, DirectorRepository, MovieRepository, UserRepository};
use App\Repositories\Interfaces\Api\{ApiAuthRepositoryInterface, ApiDirectorRepositoryInterface, ApiMovieRepositoryInterface};
use App\Repositories\Interfaces\{AuthRepositoryInterface, DirectorRepositoryInterface, MovieRepositoryInterface, UserRepositoryInterface};
use App\Services\Api\{ApiAuthService, ApiMovieService};
use Laravel\Passport\Passport;
use App\Services\{MailService, MovieService};
use App\Services\Interfaces\Api\{ApiAuthServiceInterface, ApiMovieServiceInterface};
use App\Services\Interfaces\{MailServiceInterface, MovieServiceInterface};
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

        $this->app->bind(ApiDirectorRepositoryInterface::class, ApiDirectorRepository::class);
        $this->app->bind(DirectorRepositoryInterface::class, DirectorRepository::class);

        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(MailServiceInterface::class, MailService::class);
    }

    public function boot(): void
    {
        Paginator::useBootstrap();

        Passport::enablePasswordGrant();
        Passport::tokensExpireIn(now()->addDays(15));
        Passport::refreshTokensExpireIn(now()->addDays(30));
    }
}
