<?php

use App\Helpers\General\Helper;

if (! function_exists('appName')) {
    /**
     * Helper to grab the application name.
     *
     * @return string
     */
    function appName()
    {
        return config('app.name');
    }
}

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

if (! function_exists('urlSanitize')) {
    /**
     * Remove http://, www., and slashes from the URL.
     *
     * @param string|array $value
     * @return string|array
     */
    function urlSanitize($value)
    {
        return Helper::urlSanitize($value);
    }
}
