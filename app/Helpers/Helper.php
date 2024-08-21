<?php

namespace App\Helpers;

use Composer\Pcre\Preg;
use Spatie\Url\Url as SpatieUrl;

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
     * A URL formatted according to the specified format.
     *
     * @param string $value URL links
     * @param int|null $limit Length string will be truncated to, including suffix
     * @param bool $scheme Show or remove URL schemes
     * @param bool $trailingSlash Show or remove trailing slash
     * @return string
     */
    public static function urlFormat(string $value, ?int $limit = null, bool $scheme = true, bool $trailingSlash = true)
    {
        $sUrl = SpatieUrl::fromString($value);
        $hostLen = strlen($sUrl->getScheme() . '://' . $sUrl->getHost());
        $limit ??= strlen($value);

        // Optionally strip scheme
        if ($scheme === false) {
            $value = Preg::replace('{^http(s)?://}', '', $value);
            $hostLen = strlen($sUrl->getHost());
        }

        // Optionally strip trailing slash
        if ($trailingSlash === false) {
            $value = rtrim($value, '/');
        }

        if (strlen($value) > $limit) {
            $trimMarker = '...';
            $pathLen = $limit - $hostLen;
            $firstPartLen = $hostLen + intval(($pathLen - 1) * 0.5) + strlen($trimMarker);
            $lastPartLen = -abs($limit - $firstPartLen);

            return mb_strimwidth($value, 0, $firstPartLen, $trimMarker) . substr($value, $lastPartLen);
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
            ->reject(fn($value) => !preg_match('/^[a-zA-Z\-]+$/', $value))
            ->unique()->sort()
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
            // remove ., ..,
            ->reject(fn($value) => in_array($value, ['.', '..']))
            // remove file with extension
            ->filter(fn($value) => !preg_match('/\.[a-z]+$/', $value))
            // remove array value which is in config('urlhub.reserved_keyword')
            ->reject(fn($value) => in_array($value, config('urlhub.reserved_keyword')))
            ->toArray();
    }
}
