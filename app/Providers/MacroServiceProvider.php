<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
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
        // A SQLite UDF for the REGEXP keyword that mimics the behavior in MySQL.
        if (DB::connection() instanceof \Illuminate\Database\SQLiteConnection) {
            DB::connection()->getPdo()->sqliteCreateFunction('REGEXP', function (string $pattern, string $value) {
                mb_regex_encoding('UTF-8');

                return mb_ereg($pattern, $value) !== false ? 1 : 0;
            });
        }
    }
}
