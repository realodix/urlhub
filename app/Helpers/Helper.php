<?php

namespace App\Helpers;

use Illuminate\Support\Str;
use Spatie\Url\Url as SpatieUrl;

class Helper
{
    /**
     * Parse any User Agent
     *
     * @return \DeviceDetector\DeviceDetector
     */
    public static function deviceDetector()
    {
        $device = new \DeviceDetector\DeviceDetector(request()->userAgent());
        $device->setCache(new \DeviceDetector\Cache\LaravelCache);
        $device->parse();

        return $device;
    }

    /**
     * A URL formatted according to the specified format.
     *
     * @param string   $value         URL links
     * @param int|null $limit         Length string will be truncated to, including suffix
     * @param bool     $scheme        Show or remove URL schemes
     * @param bool     $trailingSlash Show or remove trailing slash
     * @return string
     */
    public static function urlFormat(string $value, ?int $limit = null, bool $scheme = true, bool $trailingSlash = true)
    {
        $sUrl = SpatieUrl::fromString($value);
        $hostLen = strlen($sUrl->getScheme().'://'.$sUrl->getHost());
        $urlLen = strlen($value);
        $limit = $limit ?? $urlLen;

        // Strip the URL scheme
        if ($scheme === false) {
            $value = preg_replace('{^http(s)?://}', '', $value);
            $hostLen = strlen($sUrl->getHost());
            $urlLen = strlen($value);
        }

        // Strip the trailing slash from the end of the string
        if ($trailingSlash === false) {
            $value = rtrim($value, '/');
        }

        $pathLen = $limit - $hostLen;

        if ($urlLen > $limit) {
            // The string length returned by Str::limit() does not include the suffix,
            // so it needs to be adjusted to match the expected limit.
            $truncatedStrLen = Str::of($value)->limit($limit)->length();
            $adjLimit = $limit - ($truncatedStrLen - $limit);

            $firstSide = $hostLen + intval(($pathLen - 1) * 0.5);
            $lastSide = -abs($adjLimit - $firstSide);

            return Str::limit($value, $firstSide).substr($value, $lastSide);
        }

        return $value;
    }

    /**
     * List of potentially colliding routes with shortened link keywords
     *
     * @return array<string>
     */
    public static function routeList(): array
    {
        $route = array_map(
            fn (\Illuminate\Routing\Route $route) => $route->uri,
            \Illuminate\Support\Facades\Route::getRoutes()->get()
        );

        return collect($route)
            // ex. foobar/{route_param?} => foobar
            ->map(fn ($value) => preg_replace('/(\/{)([a-zA-Z]+)(\?})$/', '', $value))
            // Remove foo/bar
            ->map(fn ($value) => preg_replace('/^([a-zA-Z-_]+)\/([a-zA-Z-\/{}\.]+)/', '', $value))
            // Remove '{route_param}' or '+{route_param}'
            ->map(fn ($value) => preg_replace('/^(\+?)({)([a-zA-Z]+)(})/', '', $value))
            // Remove '/'
            ->map(fn ($value) => preg_replace('/\//', '', $value))
            // Remove empty value
            ->reject(fn ($value) => empty($value))
            ->unique()
            ->sort()
            ->toArray();
    }

    /**
     * List of files/folders in the public/ directory that will potentially collide
     * with shortened link keywords.
     *
     * @return array<string>
     */
    public static function publicPathCollisionList(): array
    {
        $publicPath = scandir(public_path());

        if ($publicPath === false) {
            return [];
        }

        return collect($publicPath)
            // remove ., ..,
            ->reject(fn ($value) => in_array($value, ['.', '..']))
            // remove file with extension
            ->filter(fn ($value) => ! preg_match('/\.[a-z]+$/', $value))
            // remove array value which is in config('urlhub.reserved_keyword')
            ->reject(fn ($value) => in_array($value, config('urlhub.reserved_keyword')))
            ->toArray();
    }
}
