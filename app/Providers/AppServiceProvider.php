<?php

namespace App\Providers;

use App\Repositories\Interfaces\MovieRepositoryInterface;
use App\Repositories\MovieRepository;
use App\Services\Interfaces\ApiMovieServiceInterface;
use App\Services\ApiMovieService;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(MovieRepositoryInterface::class, MovieRepository::class);
        $this->app->bind(ApiMovieServiceInterface::class, ApiMovieService::class);
    }

    public function boot(): void
    {
        Paginator::useBootstrap();
    }
}
