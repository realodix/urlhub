<?php

use App\Helpers\Helper;

if (! function_exists('urlDisplay')) {
    /**
     * Display the link according to what You need.
     *
     * @param string $url    URL or Link.
     * @param bool   $scheme Show or remove URL schemes.
     * @param int    $limit  Length string will be truncated to, including suffix.
     * @return string|\Illuminate\Support\Stringable
     */
    function urlDisplay($url, $scheme = true, $limit = null)
    {
        return Helper::urlDisplay($url, $scheme, $limit);
    }
}
