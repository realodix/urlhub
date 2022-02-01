<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Vinkla\Hashids\Facades\Hashids;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     */
    public const HOME = '/';

    /**
     * The path to the "admin" route for your application.
     */
    public const ADMIN = '/admin';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::prefix('api')
                ->middleware('api')
                ->namespace('App\Http\Controllers\API')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->namespace('App\Http\Controllers')
                ->group(base_path('routes/web.php'));
        });

        $this->routeModelBinding();
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }

    private function routeModelBinding()
    {
        Route::bind('user', function ($value, $route) {
            return \App\Models\User::whereName($value)->firstOrFail();
        });

        Route::bind('user_hashId', function ($value, $route) {
            return $this->hashidsDecoder(\App\Models\User::class, $value);
        });

        Route::bind('url_hashId', function ($value, $route) {
            return $this->hashidsDecoder(\App\Models\Url::class, $value);
        });
    }

    private function hashidsDecoder($model, $routeKey)
    {
        $id = Hashids::connection($model)->decode($routeKey)[0] ?? null;
        $modelInstance = resolve($model);

        return $modelInstance->findOrFail($id);
    }
}
