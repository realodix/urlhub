<?php

namespace App\Providers;

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
    }
}
