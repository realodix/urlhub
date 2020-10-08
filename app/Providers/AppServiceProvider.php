<?php

namespace App\Providers;

use App\Services\ConfigService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

/**
 * @codeCoverageIgnore
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Keeping configuration (config\urlhub.php) values of an invalid value.
        (new ConfigService())->configGuard();

        // A SQLite UDF for the REGEXP keyword that mimics the behavior in MySQL. 
        if (DB::Connection() instanceof \Illuminate\Database\SQLiteConnection) {
            DB::connection()->getPdo()->sqliteCreateFunction('REGEXP', function ($pattern, $value) {
                mb_regex_encoding('UTF-8');

                return false !== mb_ereg($pattern, $value) ? 1 : 0;
            });
        }
    }
}
