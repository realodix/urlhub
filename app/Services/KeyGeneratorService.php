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
            do {
                $randomString = $this->randomString();
            } while ($this->ensureStringCanBeUsedAsKey($randomString) == false);

            return $randomString;
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

        $path = preg_replace(
            '/[^'.self::ALPHABET.']/i',
            '',
            $url->getPath().$url->getQuery().$url->getFragment()
        );

        if (($length - strlen($path)) <= 2) {
            $f1 = Str::charAt(str_replace('www.', '', $url->getHost()), 0);
            $f2 = Str::charAt($path, 0);

            // YES https://github.com/laravel/laravel/issues
            // NO  https://github.com/laravel/laravel
            if (count($url->getSegments()) > 2) {
                $f3 = substr($url->getSegment(2), -1);
                $f4 = substr($path, ($length-3) * -1);

                return strtolower($f1.$f2.$f3.$f4);
            }

            // 2 => 1 char for f1 and 1 char for f2
            // -1 => start from the end
            $f3 = substr($path, ($length-2) * -1);

            return strtolower($f1.$f2.$f3);
        }

        return Str::of($value)
            ->replaceMatches('/[^'.self::ALPHABET.']/i', '')
            ->substr($length * -1)
            ->lower();
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
