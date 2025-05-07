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

    /**
     * The maximum number of random key generation attempts
     *
     * @var int
     */
    const MAX_RANDOM_STRING_ATTEMPTS = 200;

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

        // If the string is still not unique, then generate a random string
        // until it is unique
        $attempts = 0;
        do {
            if ($attempts >= self::MAX_RANDOM_STRING_ATTEMPTS) {
                throw new \App\Exceptions\CouldNotGenerateUniqueKeyException;
            }

            $randomString = $this->randomString();
            $attempts++;
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
        return substr(hash('xxh3', $value), 0, $this->settings->key_len);
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
        $length = $this->settings->key_len;

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
        $keyExists = Url::where('keyword', $keyword)
            ->where('is_custom', false)->exists();
        $customKeyExists = Url::whereRaw('LOWER(keyword) = ?', [strtolower($keyword)])
            ->where('is_custom', true)->exists();
        $keyIsReserved = $this->reservedKeyword()->contains(strtolower($keyword));

        if ($keyExists || $customKeyExists || $keyIsReserved) {
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
     * Calculate the maximum number of unique random strings that can be generated.
     */
    public function capacity(): int
    {
        // max() is used to avoid negative values
        return max($this->maxUniqueStrings() - $this->reservedKeywordSpaceUsed(), 0);
    }

    /**
     * Calculate the remaining capacity for generating new random strings.
     */
    public function remainingCapacity(): int
    {
        // max() is used to avoid negative values
        return max($this->capacity() - $this->totalKeywordSpaceUsed(), 0);
    }

    /**
     * Calculates the total occupancy within the available keyspace.
     */
    public function totalKeywordSpaceUsed(): int
    {
        return $this->standardKeywordSpaceUsed() + $this->customKeywordSpaceUsed();
    }

    /**
     * Calculates the keyspace used by standard keywords, based on the current
     * character length configuration.
     */
    public function standardKeywordSpaceUsed(): int
    {
        $length = app(GeneralSettings::class)->key_len;

        return Url::where('is_custom', false)
            ->whereRaw('LENGTH(keyword) = ?', [$length])
            ->count();
    }

    /**
     * Calculates the amount of keyspace used by custom keywords based on their
     * composition and the current character length configuration. Custom keywords
     * use more space within the total capacity than their simple count suggests
     * due to the generator potentially needing to avoid case variants.
     */
    public function customKeywordSpaceUsed(): int
    {
        $length = app(GeneralSettings::class)->key_len;

        // Evaluate by categorizing them into several types:
        $regular = Url::where('is_custom', true)
            ->composition('alpha', $length)
            ->count() * pow(2, $length);
        $numOrSymbol = Url::where('is_custom', true)
            ->composition('has_num_or_symbol', $length)
            ->count() * pow(2, $length - 1);
        $numAndSymbol = Url::where('is_custom', true)
            ->composition('has_num_and_symbol', $length)
            ->count() * pow(2, $length - 2);
        $onlyNumSymbol = Url::where('is_custom', true)
            ->composition('only_num_symbol', $length)->count();

        return $regular + $numOrSymbol + $numAndSymbol + $onlyNumSymbol;
    }

    /**
     * Calculates the maximum number of unique strings that can be generated using
     * the allowed character and the specified keyword length.
     */
    public function maxUniqueStrings(): int
    {
        $charSize = strlen(self::ALPHABET);
        $strLen = $this->settings->key_len;

        $maxUniqueStrings = pow($charSize, $strLen);

        // Check if it's an integer that exceeds the maximum allowed value
        // or if the result is not an integer (due to overflow)
        if ($maxUniqueStrings > PHP_INT_MAX || !is_int($maxUniqueStrings)) {
            return PHP_INT_MAX;
        }

        return $maxUniqueStrings;
    }

    /**
     * Calculates the estimated impact of reserved keywords on the total keyspace,
     * considering case variants.
     *
     * This weighting estimates how many potential keywords are effectively
     * unavailable because a single reserved keyword must be avoided in all its
     * potential case variants. The result represents the estimated portion of
     * the keyspace considered occupied by these reserved keywords.
     */
    public function reservedKeywordSpaceUsed(): int
    {
        $settings = app(GeneralSettings::class);
        $count = $this->reservedKeyword()
            ->filter(fn($value) => strlen($value) == $settings->key_len)
            ->count();

        return $count * pow(2, $settings->key_len);
    }
}
