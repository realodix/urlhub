<?php

use App\Helpers\General\GeneralHelper;
use Realodix\Utils\Url;

if (! function_exists('uHub')) {
    /**
     * Shortcut to access configuration values in '/config/urlhub.php'.
     *
     * Example:
     * - uHub('option') is equal to config('urlhub.option').
     *
     * @codeCoverageIgnore
     *
     * @param  string  $value
     * @return mixed
     */
    function uHub(string $value)
    {
        return config('urlhub.'.$value);
    }
}

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
     * @param  string  $url  URL or Link.
     * @param  bool  $scheme  Show or remove URL schemes.
     * @param  int  $limit  Length string will be truncated to, including
     *                      suffix.
     * @return string
     */
    function urlDisplay(string $url, bool $scheme = true, int $limit = null)
    {
        return resolve(GeneralHelper::class)->urlDisplay($url, $scheme, $limit);
    }
}

if (! function_exists('urlSanitize')) {
    /**
     * Remove http://, www., and slashes from the URL.
     *
     * @param  mixed  $value
     * @return mixed
     */
    function urlSanitize($value)
    {
        return resolve(GeneralHelper::class)->urlSanitize($value);
    }
}
