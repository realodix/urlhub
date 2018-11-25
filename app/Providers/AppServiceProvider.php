<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (! file_exists(public_path('/mix-manifest.json'))) {
            return abort('503', 'The Mix manifest does not exist. See https://github.com/realodix/plur#compiling-assets-with-laravel-mix');
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
