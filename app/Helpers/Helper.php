<?php

namespace App\Helpers;

use Illuminate\Support\Str;
use Illuminate\Support\Stringable;
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
     * Display the link according to what You need.
     *
     * @param string   $value         URL links
     * @param int|null $limit         Length string will be truncated to, including suffix
     * @param bool     $scheme        Show or remove URL schemes
     * @param bool     $trailingSlash Show or remove trailing slash
     */
    public static function urlDisplay(
        string $value,
        ?int $limit = null,
        bool $scheme = true,
        bool $trailingSlash = true
    ): string|Stringable {
        $sUrl = SpatieUrl::fromString($value);
        $hostLen = strlen($sUrl->getScheme().'://'.$sUrl->getHost());
        $urlLen = strlen($value);
        $limit = $limit ?? $urlLen;

        if ($scheme === false) {
            $value = preg_replace('{^http(s)?://}', '', $value);
            $hostLen = strlen($sUrl->getHost());
            $urlLen = strlen($value);
        }

        if ($trailingSlash === false) {
            $value = rtrim($value, '/');
        }

        $pathLen = $limit - $hostLen;

        if ($urlLen > $limit) {
            // The length of the string returned by str()->limit() does not include the suffix, so
            // it needs to be adjusted so that the length of the string matches the expected limit.
            $adjLimit = $limit - (strlen((string) Str::of($value)->limit($limit)) - $limit);

            $firstSide = $hostLen + intval(($pathLen - 1) * 0.5);
            $lastSide = -abs($adjLimit - $firstSide);

            return Str::of($value)->limit($firstSide).substr($value, $lastSide);
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
     * Get list of public path
     */
    public static function publicPathList(): array
    {
        return collect(scandir(public_path()))
            ->reject(fn ($value) => in_array($value, ['.', '..']))
            // remove file with extension
            ->reject(fn ($value) => preg_match('/\.[^.]+/', $value))
            // remove array value which is in config('urlhub.reserved_keyword')
            ->reject(fn ($value) => in_array($value, config('urlhub.reserved_keyword')))
            ->toArray();
    }
}
