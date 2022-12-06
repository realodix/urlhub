<?php

namespace App\Providers;

use App\Models\User;
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
     * Typically, users are redirected here after authentication.
     */
    public const HOME = '/';

    /**
     * The path to the "admin" route for your application.
     */
    public const ADMIN = '/admin';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
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
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }

    /**
     * @return void
     */
    private function routeModelBinding()
    {
        Route::bind('user', function (string $value): User {
            return User::whereName($value)->firstOrFail();
        });

        Route::bind('user_hashId', function (string $value) {
            return $this->hashidsDecoder(User::class, $value);
        });

        Route::bind('su_hashId', function (string $value) {
            return $this->hashidsDecoder(\App\Models\Url::class, $value);
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection
     */
    private function hashidsDecoder(string $model, string $routeKey)
    {
        /** @var \Vinkla\Hashids\Facades\Hashids */
        $hashids = Hashids::connection($model);
        $id = $hashids->decode($routeKey)[0] ?? null;

        return resolve($model)->findOrFail($id);
    }
}
