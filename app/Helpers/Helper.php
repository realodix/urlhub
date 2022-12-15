<?php

namespace App\Helpers;

use Illuminate\Support\Str;
use Illuminate\Support\Stringable;
use Spatie\Url\Url as SpatieUrl;
use Symfony\Component\HttpFoundation\IpUtils;

class Helper
{
    /**
     * Anonymize an IPv4 or IPv6 address.
     *
     * @param string|null $address
     */
    public static function anonymizeIp($address): string
    {
        if (config('urlhub.anonymize_ip_addr') === false) {
            return $address;
        }

        return IPUtils::anonymize($address);
    }

    /**
     * Display the link according to what You need.
     *
     * @param string $url    URL or Link
     * @param int    $limit  Length string will be truncated to, including suffix
     * @param bool   $scheme Show or remove URL schemes
     */
    public static function urlDisplay(
        string $url, int $limit = null, bool $scheme = true, bool $trailingSlash = true
    ): string|Stringable {
        $sUrl = SpatieUrl::fromString($url);
        $hostLen = strlen($sUrl->getScheme().'://'.$sUrl->getHost());
        $urlLen = strlen($url);
        $limit = $limit ?? $urlLen;

        if ($scheme === false) {
            $url = preg_replace('{^http(s)?://}', '', $url);
            $hostLen = strlen($sUrl->getHost());
            $urlLen = strlen($url);
        }

        if ($trailingSlash === false) {
            $url = rtrim($url, '/');
        }

        $pathLen = $limit - $hostLen;

        if ($urlLen > $limit) {
            // The length of the string returned by str()->limit() does not include the suffix, so
            // it needs to be adjusted so that the length of the string matches the expected limit.
            $adjLimit = $limit - (strlen((string) Str::of($url)->limit($limit)) - $limit);

            $firstSide = $hostLen + intval(($pathLen - 1) * 0.5);
            $lastSide = -abs($adjLimit - $firstSide);

            return Str::of($url)->limit($firstSide).substr($url, $lastSide);
        }

        return $url;
    }
}
