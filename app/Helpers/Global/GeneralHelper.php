<?php

use App\Helpers\Helper;

if (! function_exists('urlDisplay')) {
    /**
     * Display the link according to what You need.
     *
     * @param string $url
     * @param int    $limit
     * @param bool   $scheme
     * @return string|\Illuminate\Support\Stringable
     */
    function urlDisplay($url, $limit = null, $scheme = true)
    {
        return Helper::urlDisplay($url, $limit, $scheme);
    }
}

if (! function_exists('compactNumber')) {
    /**
     * \App\Helpers\Helper::compactNumber()
     *
     * @param int $value
     * @return int|string
     */
    function compactNumber($value)
    {
        return Helper::compactNumber($value);
    }
}
