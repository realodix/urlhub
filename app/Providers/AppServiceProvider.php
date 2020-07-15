<?php

namespace App\Providers;

use App\Helpers\General\ConfigValidation;
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
        if (! file_exists(public_path('/mix-manifest.json'))) {
            return abort('503', 'The Mix manifest does not exist. See https://github.com/realodix/urlhub#compiling-assets-with-laravel-mix');
        }

        (new ConfigValidation())->validateConfig();

        if (DB::Connection() instanceof \Illuminate\Database\SQLiteConnection) {
            DB::connection()->getPdo()->sqliteCreateFunction('REGEXP', function ($pattern, $value) {
                mb_regex_encoding('UTF-8');

                return (false !== mb_ereg($pattern, $value)) ? 1 : 0;
            });
        }
    }
}
