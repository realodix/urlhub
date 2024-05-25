<?php

namespace App\Services;

use App\Models\Url;

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
            $this->verify($string) === false
            || strlen($string) < config('urlhub.keyword_length')
        ) {
            do {
                $randomString = $this->randomString();
            } while ($this->verify($randomString) == false);

            return $randomString;
        }

        return $string;
    }

    public function simpleString(string $value): string
    {
        return substr(hash('xxh3', $value), 0, config('urlhub.keyword_length'));
    }

    /**
     * @codeCoverageIgnore
     * Random\Randomizer::getBytesFromString
     *
     * https://www.php.net/manual/en/random-randomizer.getbytesfromstring.php
     */
    public function randomString(): string
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
     * Verifies whether a string can be used as a keyword
     */
    public function verify(string $value): bool
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
        $length = (int) config('urlhub.keyword_length');

        return Url::whereRaw('LENGTH(keyword) = ?', [$length])
            ->whereRaw('keyword REGEXP "^[a-zA-Z0-9]{'.$length.'}$"')
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
