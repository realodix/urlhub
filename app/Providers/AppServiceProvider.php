<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Prevent destructive commands from running in production environments.
        DB::prohibitDestructiveCommands($this->app->isProduction());

        // Set the timezone based on the user's timezone
        Carbon::macro('inUserTimezone', static function () {
            $userTimezone = auth()->user()->timezone ?? config('app.timezone');

            return self::this()->copy()->tz($userTimezone);
        });
    }
}
