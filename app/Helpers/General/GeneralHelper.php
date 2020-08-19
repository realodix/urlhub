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
        $pathLen = strlen($sUrl->getPath());

        if ($pathLen == 1) {
            $url = rtrim($url, '/').'';
        }

        // Remove URL schemes
        if (! $scheme) {
            $url = $this->urlRemoveScheme($url);
            $hostLen = strlen($sUrl->getHost());
        }

        $trimPathLen = $length - $hostLen;

        if ((1 >= $trimPathLen) && ($trimPathLen <= 9)) {
            $length -= 3;

            return Str::limit($url, $length);
        }

        $firstSide = intval($length * 0.6); // use intval to prevent float
        $lastSide = (($length - $firstSide) * -1) + 3; // + 3 dots from Str::limit()

        if ($urlLen > $length && $length > 0) {
            if ($trimPathLen == 10) {
                $firstSide = $hostLen + 4;
                $lastSide = (($length - $firstSide) * -1) + 3; // + 3 dots from Str::limit()

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
