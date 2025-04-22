<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;

class MacroServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Set the timezone based on the user's timezone
        Carbon::macro('inUserTz', static function () {
            $userTimezone = auth()->user()->timezone ?? config('app.timezone');

            return self::this()->copy()->tz($userTimezone);
        });
    }
}
