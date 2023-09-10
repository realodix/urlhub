<?php

namespace App\Services;

use App\Models\Url;

class KeyGeneratorService
{
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
        // If step 1 fail (the string is used or cannot be used), then the generator
        // must generate a unique random string until it finds a string that can
        // be used as a key
        if ($this->assertStringCanBeUsedAsKey($key) === false) {
            $key = $this->generateRandomString();
        }

        return $key;
    }

    /**
     * Take some characters at the end of the string and remove all characters that
     * are not in the specified character set. If the string contains uppercase
     * letters, it must be converted to lowercase letters.
     */
    public function generateSimpleString(string $value): string
    {
        // Retrieve only characters that match the predefined specifications
        $cleanedChar = (string) preg_replace('/[^'.config('urlhub.hash_char').']/i', '', $value);

        return mb_strtolower(substr($cleanedChar, config('urlhub.hash_length') * -1));
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

        $characters = config('urlhub.hash_char');
        $length = config('urlhub.hash_length');

        do {
            $urlKey = $generator->generateString($length, $characters);
        } while ($this->assertStringCanBeUsedAsKey($urlKey) == false);

        return $urlKey;
    }

    /**
     * Check if string can be used as a keyword.
     *
     * This function will check under several conditions:
     * 1. If the string is already used in the database
     * 2. If the string is used as a reserved keyword
     * 3. If the string is used as a route path
     *
     * If any or all of the conditions are true, then the keyword cannot be used.
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
        $characters = strlen(config('urlhub.hash_char'));
        $length = config('urlhub.hash_length');

        // for testing purposes only
        // tests\Unit\Middleware\UrlHubLinkCheckerTest.php
        if ($length === 0) {
            return 0;
        }

        return (int) pow($characters, $length);
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
        $regexPattern = '['.config('urlhub.hash_char').']{'.$hashLength.'}';

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
        $maxCapacity = $this->maxCapacity();
        $usedCapacity = $this->usedCapacity();

        // prevent negative values
        return max($maxCapacity - $usedCapacity, 0);
    }

    /**
     * Calculate the percentage of the remaining unique random strings that can
     * be generated from the total number of unique random strings that can be
     * generated (in percent) with the specified precision (in decimal places)
     * and return the result as a string.
     */
    public function idleCapacityInPercent(int $precision = 2): string
    {
        $maxCapacity = $this->maxCapacity();
        $remaining = $this->idleCapacity();
        $result = round(($remaining / $maxCapacity) * 100, $precision);

        $lowerBoundInPercent = 1 / (10 ** $precision);
        $upperBoundInPercent = 100 - $lowerBoundInPercent;
        $lowerBound = $lowerBoundInPercent / 100;
        $upperBound = 1 - $lowerBound;

        if ($remaining > 0 && $remaining < ($maxCapacity * $lowerBound)) {
            $result = $lowerBoundInPercent;
        } elseif (($remaining > ($maxCapacity * $upperBound)) && ($remaining < $maxCapacity)) {
            $result = $upperBoundInPercent;
        }

        return $result.'%';
    }
}
