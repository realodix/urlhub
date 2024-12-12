<?php

namespace App\Helpers;

use Composer\Pcre\Preg;

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
        $uri = \Illuminate\Support\Uri::of($value);
        $schemePrefix = $scheme && $uri->scheme() ? $uri->scheme() . '://' : '';

        // Strip scheme if not required
        if (!$scheme) {
            $value = Preg::replace('/^https?:\/\//', '', $value);
        }

        // Remove trailing slash if not required
        if (!$trailingSlash) {
            $value = rtrim($value, '/');
        }

        $limit = $limit ?? strlen($value);
        $hostLength = strlen($schemePrefix . $uri->host());

        // Truncate the URL if necessary
        if (strlen($value) > $limit) {
            $trimMarker = '...';
            $adjustedLimit = $limit - strlen($trimMarker);

            // Handle cases where host is too long or the limit is shorter than the host
            if ($hostLength >= $maxHostLength || $hostLength >= $adjustedLimit) {
                $firstHalf = mb_substr($value, 0, intval($adjustedLimit * 0.8));
                $secondHalf = mb_substr($value, -intval($adjustedLimit * 0.2));

                return $firstHalf . $trimMarker . $secondHalf;
            }

            return \Illuminate\Support\Str::limit($value, $adjustedLimit, $trimMarker);
        }

        return $value;
    }

    /**
     * List of potentially colliding routes with shortened link keywords.
     *
     * @return array
     */
    public static function routeCollisionList()
    {
        return collect(\Illuminate\Support\Facades\Route::getRoutes()->get())
            ->map(fn(\Illuminate\Routing\Route $route) => $route->uri)
            ->pipe(fn($value) => self::collisionCandidateFilter($value))
            ->toArray();
    }

    /**
     * List of files/folders in the public/ directory that will potentially collide
     * with shortened link keywords.
     *
     * @return array
     */
    public static function publicPathCollisionList()
    {
        $publicPathList = scandir(public_path());
        if ($publicPathList === false) {
            return [];
        }

        return collect($publicPathList)
            ->pipe(fn($value) => self::collisionCandidateFilter($value))
            ->toArray();
    }

    /**
     * @param \Illuminate\Support\Collection $value
     * @return \Illuminate\Support\Collection
     */
    public static function collisionCandidateFilter($value)
    {
        return collect($value)
            ->filter(fn($value) => Preg::isMatch('/^([0-9a-zA-Z\-])+$/', $value))
            ->reject(fn($value) => in_array($value, config('urlhub.reserved_keyword')))
            ->unique();
    }
}
