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
        // Set the timezone based on the user's timezone
        Carbon::macro('inUserTimezone', static function () {
            $userTimezone = auth()->user()->timezone ?? config('app.timezone');

            return self::this()->copy()->tz($userTimezone);
        });

        // Prevent destructive commands from running in production environments.
        DB::prohibitDestructiveCommands($this->app->isProduction());

        // A SQLite UDF for the REGEXP keyword that mimics the behavior in MySQL.
        if (DB::connection() instanceof \Illuminate\Database\SQLiteConnection) {
            DB::connection()->getPdo()->sqliteCreateFunction('REGEXP', function (string $pattern, string $value) {
                mb_regex_encoding('UTF-8');

                return mb_ereg($pattern, $value) !== false ? 1 : 0;
            });
        }
    }
}
