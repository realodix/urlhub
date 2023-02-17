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
     * @param string $url           URL or Link
     * @param int    $limit         Length string will be truncated to, including suffix
     * @param bool   $scheme        Show or remove URL schemes
     * @param bool   $trailingSlash Show or remove trailing slash
     */
    public static function urlDisplay(
        string $url,
        int $limit = null,
        bool $scheme = true,
        bool $trailingSlash = true
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

    /**
     * Convert large positive numbers in to short form like 1K+, 100K+, 199K+, 1M+, 10M+,
     * 1B+ etc.
     *
     * Based on https://gist.github.com/RadGH/84edff0cc81e6326029c
     *
     * @param int $number Number to be converted
     */
    public static function compactNumber(int $number): int|string
    {
        $nFormat = floor($number);
        $suffix = '';

        if ($number >= pow(10, 3) && $number < pow(10, 6)) {
            // 1k-999k
            $nFormat = self::numberFormatPrecision($number / pow(10, 3));
            $suffix = 'K+';

            if (($number / pow(10, 3) === 1) || ($number / pow(10, 4) === 1) || ($number / pow(10, 5) === 1)) {
                $suffix = 'K';
            }
        } elseif ($number >= pow(10, 6) && $number < pow(10, 9)) {
            // 1m-999m
            $nFormat = self::numberFormatPrecision($number / pow(10, 6));
            $suffix = 'M+';

            if (($number / pow(10, 6) === 1) || ($number / pow(10, 7) === 1) || ($number / pow(10, 8) === 1)) {
                $suffix = 'M';
            }
        } elseif ($number >= pow(10, 9) && $number < pow(10, 12)) {
            // 1b-999b
            $nFormat = self::numberFormatPrecision($number / pow(10, 9));
            $suffix = 'B+';

            if (($number / pow(10, 9) === 1) || ($number / pow(10, 10) === 1) || ($number / pow(10, 11) === 1)) {
                $suffix = 'B';
            }
        } elseif ($number >= pow(10, 12)) {
            // 1t+
            $nFormat = self::numberFormatPrecision($number / pow(10, 12));
            $suffix = 'T+';

            if (($number / pow(10, 12) === 1) || ($number / pow(10, 13) === 1) || ($number / pow(10, 14) === 1)) {
                $suffix = 'T';
            }
        }

        return ! empty($nFormat.$suffix) ? $nFormat.$suffix : 0;
    }

    /**
     * Alternative to make number_format() not to round numbers up.
     *
     * Based on https://stackoverflow.com/q/3833137
     *
     * @param float $number    Number to be formatted
     * @param int   $precision Number of decimal points to round to
     */
    public static function numberFormatPrecision(float $number, int $precision = 2): float
    {
        return floor($number * pow(10, $precision)) / pow(10, $precision);
    }
}
