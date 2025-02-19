<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Class HelperServiceProvider.
 */
class HelperServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function boot()
    {
        $rdi = new \RecursiveDirectoryIterator(app_path('Helpers'.DIRECTORY_SEPARATOR.'Global'));
        $rit = new \RecursiveIteratorIterator($rdi);

        while ($rit->valid()) {
            if (
                !$rit->isDot()
                && $rit->isFile()
                && $rit->isReadable()
                && $rit->current()->getExtension() === 'php'
                && strpos($rit->current()->getFilename(), 'Helper')
            ) {
                require $rit->key();
            }

            $rit->next();
        }
    }
}
