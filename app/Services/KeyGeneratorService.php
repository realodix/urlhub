<?php

namespace App\Services;

use App\Models\Url;
use App\Settings\GeneralSettings;
use Composer\Pcre\Preg;
use Illuminate\Support\Collection;

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
        $keywordExists = Url::where('keyword', $keyword)->exists();
        $keywordIsReserved = $this->reservedKeyword()->contains($keyword);

        if ($keywordExists || $keywordIsReserved) {
            return false;
        }

        return true;
    }

    /**
     * The keywords that are currently in use as reserved keywords.
     */
    public function reservedKeyword(): Collection
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
     */
    public function reservedActiveKeyword(): Collection
    {
        $reservedKeyword = $this->reservedKeyword();
        $activeKeyword = Url::pluck('keyword')->toArray();

        return $reservedKeyword->intersect($activeKeyword);
    }

    /**
     * Returns a list of route paths that may conflict with generated keywords.
     *
     * This method retrieves all defined routes and filters them to identify potential
     * conflicts with the format used for generating keywords. This list is used to
     * prevent the generation of keywords that match existing routes.
     */
    public function routeCollisionList(): array
    {
        return collect(\Illuminate\Support\Facades\Route::getRoutes()->get())
            ->map(fn(\Illuminate\Routing\Route $route) => $route->uri)
            ->pipe(fn($paths) => $this->filterCollisionCandidates($paths))
            ->toArray();
    }

    /**
     * Returns a list of file/folder names in the public directory that may
     * conflict with generated keywords.
     *
     * This method scans the public directory and filters the results to identify
     * potential conflicts with the format used for generating keywords. This list
     * is used to prevent the generation of keywords that match existing files
     * or folders in the public directory.
     */
    public function publicPathCollisionList(): array
    {
        $publicPathList = scandir(public_path());
        // @codeCoverageIgnoreStart
        if ($publicPathList === false) {
            return [];
        }
        // @codeCoverageIgnoreEnd

        return collect($publicPathList)
            ->pipe(fn($paths) => $this->filterCollisionCandidates($paths))
            ->toArray();
    }

    /**
     * Filters a collection of strings to identify strings that could conflict
     * with generated keywords.
     */
    public function filterCollisionCandidates(array|Collection $value): Collection
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
            ->whereRaw('keyword REGEXP "^[a-zA-Z0-9]{'.$length.'}$"')
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
