<?php

namespace App\Services;

use App\Models\Url;
use App\Settings\GeneralSettings;

class KeyGeneratorService
{
    /** @var string */
    const ALPHABET = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    /**
     * Reserved for future use
     *
     * @var list<string>
     */
    const RESERVED_KEYWORD = [
        'build', 'hot', // When Vite is running in development
        'vendor',       // Packages (ex: laravel/telescope)
        'assets', 'fonts', 'images', 'img', 'storage',
    ];

    /**
     * The maximum number of times generate() can loop.
     *
     * This is a safety measure to prevents infinite loops if the length of
     * the random value is very small.
     *
     * @var int
     */
    const MAXIMUM_TRIES = 200;

    public function __construct(
        protected GeneralSettings $settings,
    ) {}

    /**
     * Generate a unique short string to use as the shortened url endings.
     *
     * This method first attempts to create a deterministic hash-based string
     * from the provided value. If the hash is already in use or is a disallowed
     * keyword, it falls back to generating a random string until a unique
     * string is found.
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
            if ($attempts >= self::MAXIMUM_TRIES) {
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
     * Verifies if a given string can be used as a unique short URL key.
     *
     * A keyword is considered invalid:
     * - It already exists as a system-generated keyword.
     * - It already exists as a custom keyword (case-insensitive).
     * - It is in the disallowed keyword list.
     *
     * @param string $keyword The keyword to verify.
     * @return bool True if the keyword is valid and available, false otherwise.
     */
    public function verify(string $keyword): bool
    {
        $keyExists = Url::where('keyword', $keyword)
            ->where('is_custom', false)->exists();
        $customKeyExists = Url::whereRaw('LOWER(keyword) = ?', [strtolower($keyword)])
            ->where('is_custom', true)->exists();
        $disallowed = $this->disallowedKeyword()->contains(strtolower($keyword));

        if ($keyExists || $customKeyExists || $disallowed) {
            return false;
        }

        return true;
    }

    /**
     * Returns all keywords that are not allowed for short URL endings.
     *
     * Includes reserved system keywords, configured blacklist, and strings
     * blocked generically.
     *
     * @return \Illuminate\Support\Collection<string>
     */
    public function disallowedKeyword()
    {
        $data = [
            self::RESERVED_KEYWORD,
            config('urlhub.blacklist_keyword'),
        ];

        return app(BlockedStringService::class)->blocked()
            ->merge($data)->flatten()->unique()->sort();
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
        return max($this->maxUniqueStrings() - $this->disallowedKeywordSpaceUsed(), 0);
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
     * Calculate the total keyspace used by custom keywords.
     *
     * The calculation is based on keyword composition and the configured keyword
     * length. Certain compositions consume more keyspace because the generator
     * avoids some case variants.
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
     * Calculates the maximum number of possible unique strings
     * based on the alphabet size and configured length.
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
     * Calculates the total keyspace consumed by disallowed keywords.
     *
     * This method accounts for all potential case variants of each disallowed
     * keyword that matches the configured key length, treating each variant as
     * a unique entry that occupies space. This provides an estimated measure of
     * how much of the total keyspace is effectively unusable.
     */
    public function disallowedKeywordSpaceUsed(): int
    {
        $settings = app(GeneralSettings::class);
        $count = $this->disallowedKeyword()
            ->filter(fn($value) => strlen($value) == $settings->key_len)
            ->count();

        return $count * pow(2, $settings->key_len);
    }
}
