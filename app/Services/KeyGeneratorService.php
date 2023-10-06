<?php

namespace App\Services;

use App\Models\Url;
use Illuminate\Support\Str;

class KeyGeneratorService
{
    private const HASH_CHAR = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    /**
     * Generate a short string that can be used as a unique key for the shortened
     * url.
     *
     * @return string A unique string to use as the shortened url key
     */
    public function urlKey(string $value): string
    {
        // Step 1
        $key = $this->generateSimpleString($value);

        // Step 2
        // If step 1 fails (the string has been used or cannot be used), then the
        // string generator must generate a unique random string
        if (
            $this->assertStringCanBeUsedAsKey($key) === false
            || strlen($key) < config('urlhub.hash_length')
        ) {
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
    public function generateRandomString(): string
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
     * 2. If the string is in the list of reserved keywords
     * 3. If the string is in the route path list
     *
     * If any or all of the above conditions are met, then the string cannot be
     * used as a keyword and must return false.
     */
    public function assertStringCanBeUsedAsKey(string $value): bool
    {
        $route = array_map(fn (\Illuminate\Routing\Route $route) => $route->uri,
            \Illuminate\Support\Facades\Route::getRoutes()->get()
        );

        $alreadyInUse = Url::whereKeyword($value)->exists();
        $isReservedKeyword = in_array($value, config('urlhub.reserved_keyword'));
        $isRoute = in_array($value, $route);

        if ($alreadyInUse || $isReservedKeyword || $isRoute) {
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
     * The maximum number of unique strings that can be generated.
     */
    public function possibleOutput(): int
    {
        $nChar = strlen(self::HASH_CHAR);
        $strLen= config('urlhub.hash_length');

        // for testing purposes only
        // tests\Unit\Middleware\UrlHubLinkCheckerTest.php
        if ($strLen === 0) {
            return 0;
        }

        return gmp_intval(gmp_pow($nChar, $strLen));
    }

    /**
     * The number of unique keywords that have been used.
     *
     * Formula:
     * totalKey = randomKey + customKey
     *
     * The length of the generated string (randomKey) and the length of the
     * `customKey` string must be identical.
     */
    public function totalKey(): int
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
    public function remainingCapacity(): int
    {
        // prevent negative values
        return max($this->possibleOutput() - $this->totalKey(), 0);
    }
}
