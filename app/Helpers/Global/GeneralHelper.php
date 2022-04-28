<?php

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Realodix\Utils\Url;

if (! function_exists('uHub')) {
    /**
     * Helper that makes the way to access the configuration value in
     * '/config/urlhub.php' becomes easier.
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
        // Validation of character types allowed in the `urlhub.hash_char`
        // configuration option
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
        return Url::display($url, $scheme, $limit);
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
        return Url::sanitize($value);
    }
}

if (! function_exists('qrCode')) {
    /**
     * QrCode Generator.
     *
     * @codeCoverageIgnore
     *
     * @param  string  $string
     */
    function qrCode($string)
    {
        return Builder::create()
            ->data($string)
            ->size(170)
            ->labelText('Scan QR Code')
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
            ->roundBlockSizeMode(new RoundBlockSizeModeMargin())
            ->build();
    }
}
