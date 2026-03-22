<?php declare(strict_types=1);

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class RateLimiterServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            if ($request->user()) {
                return Limit::perMinute(config('rate_limiter.api_authenticated'))->by($request->user()->id);
            }

            return Limit::perMinute(config('rate_limiter.api_guest'))->by($request->ip());
        });

        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(config('rate_limiter.login'))->by($request->ip());
        });
    }
}