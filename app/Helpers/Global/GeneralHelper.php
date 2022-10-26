<?php

use App\Helpers\General\Helper;

if (! function_exists('appName')) {
    /**
     * Helper to grab the application name.
     *
     * @return mixed
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
     * @param int    $limit  Length string will be truncated to, including
     *                       suffix.
     * @return string
     */
    function urlDisplay(string $url, bool $scheme = true, int $limit = null)
    {
        return Helper::urlDisplay($url, $scheme, $limit);
    }
}
