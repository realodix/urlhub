<?php

namespace App\Services;

use App\Models\Url;
use App\Settings\GeneralSettings;

class KeyGeneratorService
{
    /** @var string */
    const ALPHABET = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    public function __construct(
        protected GeneralSettings $settings,
    ) {}

    /**
     * Generate a short string that can be used as a unique key for the shortened
     * url.
     *
     * @return string A unique string to use as the shortened url key
     */
    public function generate(string $value): string
    {
        $string = $this->simpleString($value);

        if (!$this->verify($string) || strlen($string) < $this->settings->keyword_length) {
            do {
                $randomString = $this->randomString();
            } while (!$this->verify($randomString));

            return $randomString;
        }

        return $string;
    }

    public function simpleString(string $value): string
    {
        return substr(hash('xxh3', $value), 0, $this->settings->keyword_length);
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
        $length = $this->settings->keyword_length;

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
     * Verifies whether a string can be used as a keyword.
     */
    public function verify(string $value): bool
    {
        $alreadyInUse = Url::whereKeyword($value)->exists();
        $reservedKeyword = $this->reservedKeyword()->contains($value);

        if ($alreadyInUse || $reservedKeyword) {
            return false;
        }

        return true;
    }

    /**
     * The keywords that are currently in use as reserved keywords.
     *
     * @return \Illuminate\Support\Collection
     */
    public function reservedKeyword()
    {
        $data = [
            config('urlhub.reserved_keyword'),
            \App\Helpers\Helper::routeCollisionList(),
            \App\Helpers\Helper::publicPathCollisionList(),
        ];

        return collect($data)->flatten()->unique()->sort();
    }

    /**
     * The keywords that are currently in use as reserved keywords, but on the other
     * hand also used as active keywords.
     *
     * @return \Illuminate\Support\Collection
     */
    public function reservedActiveKeyword()
    {
        $reservedKeyword = $this->reservedKeyword();
        $activeKeyword = Url::pluck('keyword')->toArray();

        return $reservedKeyword->intersect($activeKeyword);
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
        $strLen = $this->settings->keyword_length;

        $nPossibleOutput = pow($nChar, $strLen);

        if ($nPossibleOutput > PHP_INT_MAX) {
            return PHP_INT_MAX;
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
        $length = $this->settings->keyword_length;

        return Url::whereRaw('LENGTH(keyword) = ?', [$length])
            ->whereRaw('keyword REGEXP "^[a-zA-Z0-9]{' . $length . '}$"')
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
