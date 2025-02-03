<?php

namespace App\Services;

use App\Models\Url;
use App\Settings\GeneralSettings;
use Composer\Pcre\Preg;

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
        $str = $this->shortHash($value);
        if ($this->verify($str)) {
            return $str;
        }

        // If the first attempt fail, try to make the string uppercase
        $strUpper = strtoupper($str);
        if ($this->verify($strUpper)) {
            return $strUpper;
        }

        // If the second attempt fail, try to append the last url id
        $str = $this->shortHash($value . Url::latest('id')->value('id'));
        if ($this->verify($str)) {
            return $str;
        }

        // If the string is still not unique, then generate a random string
        // until it is unique
        do {
            $randomString = $this->randomString();
        } while (!$this->verify($randomString));

        return $randomString;
    }

    /**
     * Generates a truncated hash of the given string.
     *
     * @param string $value The input string to hash.
     * @return string The hashed and truncated string.
     */
    public function shortHash(string $value): string
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
        $characters = self::ALPHABET;
        $length = $this->settings->keyword_length;

        if (\PHP_VERSION_ID < 80300) {
            $charLength = strlen($characters);

            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[mt_rand(0, $charLength - 1)];
            }

            return $randomString;
        }

        $randomizer = new \Random\Randomizer;

        return $randomizer->getBytesFromString($characters, $length);
    }

    /**
     * Verifies whether a string can be used as a keyword.
     */
    public function verify(string $keyword): bool
    {
        $keywordExists = Url::whereKeyword($keyword)->exists();
        $keywordIsReserved = $this->reservedKeyword()->contains($keyword);

        if ($keywordExists || $keywordIsReserved) {
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
            $this->routeCollisionList(),
            $this->publicPathCollisionList(),
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

    /**
     * A list of route paths that could potentially conflict with shortened link
     * keywords.
     *
     * This method retrieves all defined routes and filters them to identify potential
     * conflicts with the format and reserved keywords used for shortened links.
     *
     * @return array
     */
    public function routeCollisionList()
    {
        return collect(\Illuminate\Support\Facades\Route::getRoutes()->get())
            ->map(fn(\Illuminate\Routing\Route $route) => $route->uri)
            ->pipe(fn($paths) => $this->collisionCandidateFilter($paths))
            ->toArray();
    }

    /**
     * A list of file/folder names in the public directory that could potentially
     * conflict with shortened link keywords.
     *
     * This method scans the public directory and filters the file/folder names
     * to identify potential conflicts with keywords used for shortened links.
     *
     * @return array
     */
    public function publicPathCollisionList()
    {
        $publicPathList = scandir(public_path());
        if ($publicPathList === false) {
            return [];
        }

        return collect($publicPathList)
            ->pipe(fn($paths) => $this->collisionCandidateFilter($paths))
            ->toArray();
    }

    /**
     * Filters a collection of strings to identify potential collisions with keywords.
     *
     * The resulting collection contains unique strings that "could potentially"
     * clash with generated short link keywords. These strings are considered
     * "collision candidates" because they have the same format as valid keywords
     *
     * @param \Illuminate\Support\Collection $value
     * @return \Illuminate\Support\Collection
     */
    public function collisionCandidateFilter($value)
    {
        return collect($value)
            ->filter(fn($value) => Preg::isMatch('/^([0-9a-zA-Z\-])+$/', $value))
            ->reject(fn($value) => in_array($value, config('urlhub.reserved_keyword')))
            ->unique();
    }

    /*
    |--------------------------------------------------------------------------
    | Capacity calculation
    |--------------------------------------------------------------------------
    */

    /**
     * Calculates the maximum number of unique strings that can be generated using
     * the allowed character and the specified keyword length.
     *
     * @throws \RuntimeException
     */
    public function maxUniqueStrings(): int
    {
        $charSize = strlen(self::ALPHABET);
        $strLen = $this->settings->keyword_length;

        $maxUniqueStrings = pow($charSize, $strLen);

        if ($maxUniqueStrings > PHP_INT_MAX) {
            return PHP_INT_MAX;
        }

        return $maxUniqueStrings;
    }

    /**
     * Total number of keywords
     *
     * Calculates the total number of keywords with the correct length and format.
     * The length of the generated string (random string) and the length of the
     * reserved string must be identical.
     */
    public function keywordCount(): int
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
        return max($this->maxUniqueStrings() - $this->keywordCount(), 0);
    }
}
