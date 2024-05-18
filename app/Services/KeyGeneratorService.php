<?php

namespace App\Services;

use App\Models\Url;
use Illuminate\Support\Str;
use Spatie\Url\Url as SpatieUrl;

class KeyGeneratorService
{
    const ALPHABET = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    /**
     * Generate a short string that can be used as a unique key for the shortened
     * url.
     *
     * @return string A unique string to use as the shortened url key
     */
    public function generate(string $value): string
    {
        $string = $this->simpleString($value);

        if (
            $this->ensureStringCanBeUsedAsKey($string) === false
            || strlen($string) < config('urlhub.keyword_length')
        ) {
            $string = $this->generateRandomString();
        }

        return $string;
    }

    /**
     * Simple string generator
     */
    public function simpleString(string $value): string
    {
        $url = SpatieUrl::fromString($value);
        $length = config('urlhub.keyword_length');

        $path = Str::of($url->getPath().$url->getQuery().$url->getFragment())
            // remove encoded characters
            ->replaceMatches('/%\w{2}/', '')
            ->replaceMatches('/[^'.self::ALPHABET.']/i', '');

        if (($length - $path->length()) <= 2) {
            $f1 = Str::of($url->getHost())
                ->ltrim('www.') // remove "www." if it exists
                ->charAt(0);
            $f2 = $path->charAt(0);

            // YES https://github.com/laravel/laravel/issues
            // NO  https://github.com/laravel/laravel
            if (count($url->getSegments()) > 2) {
                $f3 = Str::substr($url->getSegment(2), -1);
                $f4 = $path->substr(($length-3) * -1);

                return strtolower($f1.$f2.$f3.$f4);
            }

            // 2 => 1 char for f1 and 1 char for f2
            // -1 => start from the end
            $f3 = $path->substr(($length-2) * -1);

            return strtolower($f1.$f2.$f3);
        }

        return Str::of($value)
            ->replaceMatches('/[^'.self::ALPHABET.']/i', '')
            ->substr($length * -1)
            ->lower();
    }

    /**
     * Generate a random string of specified length. The string will only contain
     * characters from the specified character set.
     *
     * @return string The generated random string.
     */
    public function generateRandomString(): string
    {
        do {
            $urlKey = $this->getBytesFromString(self::ALPHABET, config('urlhub.keyword_length'));
        } while ($this->ensureStringCanBeUsedAsKey($urlKey) == false);

        return $urlKey;
    }

    /**
     * Random\Randomizer::getBytesFromString
     *
     * https://www.php.net/manual/en/random-randomizer.getbytesfromstring.php
     *
     * @codeCoverageIgnore
     */
    public function getBytesFromString(string $alphabet, int $length): string
    {
        if (\PHP_VERSION_ID < 80300) {
            $stringLength = strlen($alphabet);

            $result = '';
            for ($i = 0; $i < $length; $i++) {
                $result .= $alphabet[mt_rand(0, $stringLength - 1)];
            }

            return $result;
        }

        $randomizer = new \Random\Randomizer;

        return $randomizer->getBytesFromString($alphabet, $length);
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
    public function ensureStringCanBeUsedAsKey(string $value): bool
    {
        $alreadyInUse = Url::whereKeyword($value)->exists();
        $isReservedKeyword = in_array($value, config('urlhub.reserved_keyword'));
        $isRoute = in_array($value, \App\Helpers\Helper::routeList());
        $isPublicPath = in_array($value, \App\Helpers\Helper::publicPathList());

        if ($alreadyInUse || $isReservedKeyword || $isRoute || $isPublicPath) {
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
     *
     * @throws \RuntimeException
     */
    public function possibleOutput(): int
    {
        $nChar = strlen(self::ALPHABET);
        $strLen= config('urlhub.keyword_length');

        // for testing purposes only
        // tests\Unit\Middleware\UrlHubLinkCheckerTest.php
        if ($strLen < 1) {
            return 0;
        }

        $nPossibleOutput = pow($nChar, $strLen);

        if ($nPossibleOutput > PHP_INT_MAX) {
            // @codeCoverageIgnoreStart
            if (! extension_loaded('gmp')) {
                throw new \RuntimeException('The "GMP" PHP extension is required.');
            }
            // @codeCoverageIgnoreEnd

            return gmp_intval(gmp_pow($nChar, $strLen));
        }

        return $nPossibleOutput;
    }

    /**
     * Total number of keywords
     *
     * The length of the generated string (random string) and the length of the
     * reserved string must be identical.
     */
    public function totalKey(): int
    {
        $hashLength = (int) config('urlhub.keyword_length');

        return Url::whereRaw('LENGTH(keyword) = ?', [$hashLength])
            ->count();
    }

    /**
     * Calculate the number of unique random strings that can still be generated.
     */
    public function remainingCapacity(): int
    {
        // max() is used to avoid negative values
        return max($this->possibleOutput() - $this->totalKey(), 0);
    }
}
