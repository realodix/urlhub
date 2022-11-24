<?php

namespace App\Helpers;

use Illuminate\Support\Str;
use Illuminate\Support\Stringable;
use Spatie\Url\Url as SpatieUrl;

class Helper
{
    /**
     * Display the link according to what You need.
     *
     * @param string $url    URL or Link
     * @param bool   $scheme Show or remove URL schemes.
     * @param int    $limit  Length string will be truncated to, including suffix.
     */
    public static function urlDisplay(string $url, bool $scheme = true, int $limit = null): string|Stringable
    {
        $sUrl = SpatieUrl::fromString($url);
        $hostLen = strlen($sUrl->getScheme().'://'.$sUrl->getHost());
        $urlLen = strlen($url);
        $limit = $limit ?? $urlLen;

        // Remove URL schemes
        if (! $scheme) {
            $url = self::urlSanitize($url);
            $hostLen = strlen($sUrl->getHost());
            $urlLen = strlen($url);
        }

        $pathLen = $limit - $hostLen;

        // If it's only the host and has the trailing slash at the end, then remove the
        // trailing slash.
        if ($pathLen === 1) {
            $url = rtrim($url, '/\\');
        }

        if ($urlLen > $limit) {
            // The length of string truncated by str()->limit() does not include a suffix,
            // so it needs to be adjusted so that the length of the truncated string
            // matches the expected limit.
            $adjLimit = $limit - (strlen((string) Str::of($url)->limit($limit)) - $limit);

            $firstSide = $hostLen + intval(($pathLen - 1) * 0.5);
            $lastSide = -abs($adjLimit - $firstSide);

            if (((1 <= $pathLen) && ($pathLen <= 9)) || ($hostLen > $limit)) {
                return Str::of($url)->limit($adjLimit);
            }

            return Str::of($url)->limit($firstSide).substr($url, $lastSide);
        }

        return $url;
    }

    /**
     * Remove http://, www., and slashes from the URL.
     */
    public static function urlSanitize(string $url): string
    {
        return preg_replace(['{^http(s)?://}', '{www.}', '{/$}'], '', $url) ?: $url;
    }
}
