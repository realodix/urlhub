<?php

namespace App\Helpers\General;

use Illuminate\Support\Str;

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

    /**
     * The strLimit method truncates the given string at the specified length.
     *
     * @param string $string
     * @param int    $maxlength
     * @return string
     */
    public function strLimit($string, $maxlength)
    {
        $int_a = $maxlength * 0.6;
        $int_b = ($maxlength * 0.4 * -1) + 3; // + 3 dots from Str::limit()

        if (strlen($string) > $maxlength) {
            return Str::limit($string, $int_a).substr($string, $int_b);
        }

        return $string;
    }

    /**
     * @param string $value
     * @return string
     */
    public function urlRemoveScheme($value)
    {
        return str_replace([
            'http://',
            'https://',
            'www.',
        ], '', $value);
    }
}
