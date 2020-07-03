<?php

namespace App\Helpers\General;

class GeneralHelper
{
    /**
     * @codeCoverageIgnore
     * Helper that makes the way to access the configuration value in
     * '/config/urlhub.php' becomes easier.
     *
     * Example:
     * - uHub('option') is equal to config('urlhub.option').
     *
     * @param string $value
     * @return mixed
     */
    public function uHub($value)
    {
        // Validation of character types allowed in the `urlhub.hash_char`
        // configuration option
        return config('urlhub.'.$value);
    }
}
