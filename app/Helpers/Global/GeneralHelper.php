<?php

use App\Helpers\Helper;
use Illuminate\Support\Number;

if (! function_exists('urlDisplay')) {
    /**
     * Display the link according to what You need.
     *
     * @param string   $value
     * @param int|null $limit
     * @param bool     $scheme
     * @return string|\Illuminate\Support\Stringable
     */
    function urlDisplay($value, $limit = null, $scheme = true)
    {
        return Helper::urlDisplay($value, $limit, $scheme);
    }
}

if (! function_exists('n_abb')) {
    /**
     * This is modified version of Laravel Number::abbreviate() method with the
     * default value of maxPrecision is 2.
     *
     * - https://laravel.com/docs/11.x/helpers#method-number-abbreviate
     * - https://github.com/laravel/framework/blob/5d4b26e/src/Illuminate/Support/Number.php#L154
     *
     * @param int|float $number
     * @param int       $precision
     * @param int|null  $maxPrecision
     * @return bool|string
     */
    function n_abb($number, $precision = 0, $maxPrecision = 2)
    {
        return Number::abbreviate($number, $precision, $maxPrecision);
    }
}
