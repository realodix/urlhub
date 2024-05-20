<?php

namespace App\Services;

use App\Models\Url;
use Illuminate\Support\Str;

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
        $string = $this->generateSimpleString($value);

        if (
            $this->ensureStringCanBeUsedAsKey($string) === false
            || strlen($string) < config('urlhub.keyword_length')
        ) {
            do {
                $randomString = $this->generateRandomString();
            } while ($this->ensureStringCanBeUsedAsKey($randomString) == false);

            return $randomString;
        }

        return $string;
    }

    public function generateSimpleString(string $value): string
    {
        return Str::of($value)
            // Delete all characters except those in the ALPHABET constant.
            ->replaceMatches('/[^'.self::ALPHABET.']/i', '')
            // Take the specified number of characters from the end of the string.
            ->substr(config('urlhub.keyword_length') * -1)
            ->lower();
    }

    /**
     * Random\Randomizer::getBytesFromString
     *
     * https://www.php.net/manual/en/random-randomizer.getbytesfromstring.php
     *
     * @codeCoverageIgnore
     */
    public function generateRandomString(): string
    {
        $alphabet = self::ALPHABET;
        $length = config('urlhub.keyword_length');

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
