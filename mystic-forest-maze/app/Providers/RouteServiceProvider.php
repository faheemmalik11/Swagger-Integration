<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api/api.php'));

            Route::middleware('api')
            ->prefix('administration')
            ->group(base_path('routes/api/administration.php'));

            Route::middleware('api')
            ->prefix('code')
            ->group(base_path('routes/api/code.php'));

            Route::middleware('api')
            ->prefix('result')
            ->group(base_path('routes/api/results.php'));

            Route::middleware('api')
            ->prefix('maze_info')
            ->group(base_path('routes/api/maze_info.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}
