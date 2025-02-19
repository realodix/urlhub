<?php

namespace App\Helpers;

use Composer\Pcre\Preg;
use Illuminate\Support\Str;
use Illuminate\Support\Uri;

class Helper
{
    /**
     * Parse any User Agent.
     *
     * @return \DeviceDetector\DeviceDetector
     */
    public static function deviceDetector()
    {
        $device = new \DeviceDetector\DeviceDetector(request()->userAgent() ?? '');
        $device->setCache(new \DeviceDetector\Cache\LaravelCache);
        $device->parse();

        return $device;
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
    public static function urlFormat(
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
