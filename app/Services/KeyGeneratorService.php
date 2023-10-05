<?php

namespace App\Services;

use App\Models\Url;
use Illuminate\Support\Str;

class KeyGeneratorService
{
    private const HASH_CHAR = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    /**
     * Generate a short string that can be used as a unique key for shortened long
     * urls.
     *
     * @return string A unique string to use as the short url key
     */
    public function urlKey(string $value): string
    {
        // Step 1
        $key = $this->generateSimpleString($value);

        // Step 2
        // If step 1 fails (the string has been used or cannot be used), then the
        // string generator must generate a unique random string
        if ($this->assertStringCanBeUsedAsKey($key) === false) {
            $key = $this->generateRandomString();
        }

        return $key;
    }

    public function generateSimpleString(string $value): string
    {
        return Str::of($value)
            // Remove all characters except `0-9a-z-AZ`
            ->replaceMatches('/[^'.self::HASH_CHAR.']/i', '')
            // Take the specified number of characters from the end of the string.
            ->substr(config('urlhub.hash_length') * -1)
            ->lower();
    }

    /**
     * Generate a random string of specified length. The string will only contain
     * characters from the specified character set.
     *
     * @return string The generated random string
     */
    public function generateRandomString()
    {
        $factory = new \RandomLib\Factory;
        $generator = $factory->getMediumStrengthGenerator();

        do {
            $urlKey = $generator->generateString(config('urlhub.hash_length'), self::HASH_CHAR);
        } while ($this->assertStringCanBeUsedAsKey($urlKey) == false);

        return $urlKey;
    }

    /**
     * Check if string can be used as a keyword.
     *
     * This function will check under several conditions:
     * 1. If the string is already used as a key
     * 2. If the string has been used as a reserved keyword
     * 3. If the string is already registered on the route path
     *
     * If any or all of the above conditions are true, then the string cannot be
     * used as a keyword.
     */
    public function assertStringCanBeUsedAsKey(string $value): bool
    {
        $route = \Illuminate\Routing\Route::class;
        $routeCollection = \Illuminate\Support\Facades\Route::getRoutes()->get();
        $routePath = array_map(fn ($route) => $route->uri, $routeCollection);

        $isExistsInDb = Url::whereKeyword($value)->exists();
        $isReservedKeyword = in_array($value, config('urlhub.reserved_keyword'));
        $isRegisteredRoutePath = in_array($value, $routePath);

        if ($isExistsInDb || $isReservedKeyword || $isRegisteredRoutePath) {
            return false;
        }

        return true;
    }

    /*
    |--------------------------------------------------------------------------
    | Capacity calculation
    |--------------------------------------------------------------------------
    */

    /**
     * Calculate the maximum number of unique random strings that can be
     * generated
     */
    public function maxCapacity(): int
    {
        $characters = strlen(self::HASH_CHAR);
        $length = config('urlhub.hash_length');

        // for testing purposes only
        // tests\Unit\Middleware\UrlHubLinkCheckerTest.php
        if ($length === 0) {
            return 0;
        }

        return gmp_intval(gmp_pow($characters, $length));
    }

    /**
     * The number of unique random strings that have been used as the key for
     * the long url that has been shortened
     *
     * Formula:
     * usedCapacity = randomKey + customKey
     *
     * The character length and set of characters of `customKey` must be the same
     * as `randomKey`.
     */
    public function usedCapacity(): int
    {
        $hashLength = (int) config('urlhub.hash_length');
        $regexPattern = '['.self::HASH_CHAR.']{'.$hashLength.'}';

        $randomKey = Url::whereIsCustom(false)
            ->whereRaw('LENGTH(keyword) = ?', [$hashLength])
            ->count();

        $customKey = Url::whereIsCustom(true)
            ->whereRaw('LENGTH(keyword) = ?', [$hashLength])
            ->whereRaw("keyword REGEXP '".$regexPattern."'")
            ->count();

        return $randomKey + $customKey;
    }

    /**
     * Calculate the number of unique random strings that can still be generated.
     */
    public function idleCapacity(): int
    {
        // prevent negative values
        return max($this->maxCapacity() - $this->usedCapacity(), 0);
    }
}
