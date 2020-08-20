<?php

namespace App\Helpers\General;

use Illuminate\Support\Str;
use Spatie\Url\Url as SpatieUrl;

class GeneralHelper
{
    /**
     * Helper that makes the way to access the configuration value in
     * '/config/urlhub.php' becomes easier.
     * @codeCoverageIgnore
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
     * Display links or URLs as needed.
     *
     * @param string $url    URL or Link
     * @param bool   $scheme Show scheme or not
     * @param int    $length Truncates the given string at the specified length.
     *                       Set to 0 to display all of it.
     * @return string
     */
    public function urlDisplay(string $url, bool $scheme = true, int $length = 0)
    {
        $sUrl = SpatieUrl::fromString($url);
        $hostLen = strlen($sUrl->getScheme().'://'.$sUrl->getHost());
        $urlLen = strlen($url);

        // Remove URL schemes
        if (! $scheme) {
            $url = $this->urlRemoveScheme($url);
            $hostLen = strlen($sUrl->getHost());
        }

        if ($length === 0) {
            $length = $urlLen;
        }

        $pathLen = $length - $hostLen;

        // If the URL is domain only, then remove the last slash
        if ($pathLen === 1) {
            $url = rtrim($url, '/').'';
        }

        $firstSide = intval($length * 0.6); // use intval to prevent float
        $lastSide = -abs($length - $firstSide - 3); // 3 dots from Str::limit()

        if ($urlLen > $length) {
            if ((1 <= $pathLen) && ($pathLen <= 9)) {

                // 3 dots from Str::limit()
                return Str::limit($url, $length - 3);
            }

            if ($pathLen === 10) {
                $firstSide = $hostLen + 4;
                $lastSide = -abs($length - $firstSide - 3); // 3 dots from Str::limit()

                return Str::limit($url, $firstSide).substr($url, $lastSide);
            }

            return Str::limit($url, $firstSide).substr($url, $lastSide);
        }

        return $url;
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
