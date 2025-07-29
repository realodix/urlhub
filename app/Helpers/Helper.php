<?php

namespace App\Helpers;

use App\Settings\GeneralSettings;
use Composer\Pcre\Preg;
use Illuminate\Support\Str;
use Illuminate\Support\Uri;

class Helper
{
    /**
     * Check if the User Agent from the request is a bot.
     *
     * @return \Jaybizzle\CrawlerDetect\CrawlerDetect
     */
    public static function botDetector()
    {
        $crawlerDetect = app(\Jaybizzle\CrawlerDetect\CrawlerDetect::class);
        $crawlerDetect->setUserAgent(request()->userAgent() ?? '');

        return $crawlerDetect;
    }

    /**
     * Parse any User Agent.
     *
     * @return \App\Services\DeviceDetectorService
     */
    public static function deviceDetector()
    {
        $device = app(\App\Services\DeviceDetectorService::class);
        $device->setUserAgent(request()->userAgent() ?? '');
        $device->setCache(new \DeviceDetector\Cache\LaravelCache);
        $device->parse();

        return $device;
    }

    /**
     * Return the URL of a favicon for a given URL.
     *
     * @param string $url The URL to get the favicon for
     * @return string The URL of the favicon
     *
     * @throws \UnhandledMatchError
     */
    public static function faviconUrl(string $url): string
    {
        $host = Uri::of($url)->host();

        $provider = app(GeneralSettings::class)->favicon_provider;

        return match ($provider) {
            'google'     => "https://www.google.com/s2/favicons?domain={$host}",
            'duckduckgo' => "https://icons.duckduckgo.com/ip3/{$host}.ico",
        };
    }

    /**
     * Format URL links for display.
     *
     * @param string $value URL links
     * @param int|null $limit Length string will be truncated to, including suffix
     * @param bool $scheme Show or remove URL schemes
     * @param bool $trailingSlash Show or remove trailing slash
     * @param int $maxHostLength Maximum length of the host
     * @return string
     */
    public static function urlDisplay(
        string $value,
        ?int $limit = null,
        bool $scheme = true,
        bool $trailingSlash = true,
        int $maxHostLength = 45,
    ) {
        $uri = Uri::of($value);
        $schemePrefix = $scheme && $uri->scheme() ? $uri->scheme().'://' : '';

        // Strip scheme if not required
        if (!$scheme) {
            $value = Preg::replace('/^https?:\/\//', '', $value);
        }

        // Remove trailing slash if not required
        if (!$trailingSlash) {
            $value = rtrim($value, '/');
        }

        $limit = $limit ?? strlen($value);
        $hostLength = strlen($schemePrefix.$uri->host());

        // Truncate the URL if necessary
        if (strlen($value) > $limit) {
            $trimMarker = '...';
            $adjustedLimit = $limit - strlen($trimMarker);

            // Handle cases where host is too long or the limit is shorter than the host
            if ($hostLength >= $maxHostLength || $hostLength >= $adjustedLimit) {
                $firstHalf = mb_substr($value, 0, intval($adjustedLimit * 0.8));
                $secondHalf = mb_substr($value, -intval($adjustedLimit * 0.2));

                return $firstHalf.$trimMarker.$secondHalf;
            }

            return Str::limit($value, $adjustedLimit, $trimMarker);
        }

        return $value;
    }
}
