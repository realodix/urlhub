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
     * Reserved for future use
     *
     * @var array<string>
     */
    const RESERVED_KEYWORD = [
        'build', 'hot', // When Vite is running in development
        'vendor',       // Packages (ex: laravel/telescope)
        'assets', 'fonts', 'images', 'img', 'storage',
    ];

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
     * Generate a unique short string to use as the shortened url endings.
     *
     * First, attempts to create a hash-based string from the given value.
     * If that string is already taken or disallowed, repeatedly generates
     * random strings until a unique and valid key is found, up to a maximum
     * number of attempts.
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
     * Check if the given string is allowed to be used as a keyword.
     *
     * A keyword is considered invalid if:
     * - It already exists as a system-generated keyword.
     * - It already exists as a custom keyword (case-insensitive).
     * - It is in the disallowed keyword list.
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
     * Returns a list of keywords that cannot be used for generated short URLs
     * endings.
     *
     * This method consolidates various sources of disallowed keywords, including
     * reserved keywords and blacklisted keywords from configuration.
     *
     * @return \Illuminate\Support\Collection<string>
     */
    public function disallowedKeyword(): Collection
    {
        $data = [
            self::RESERVED_KEYWORD,
            $this->routeCollisionList(),
            $this->publicPathCollisionList(),
            config('urlhub.blacklist_keyword'),
        ];

        return collect($data)->flatten()->unique()->sort();
    }

    /**
     * Returns disallowed keywords that are currently active (used as short URL endings).
     *
     * @return \Illuminate\Support\Collection<string>
     */
    public function disallowedKeywordsInUse(): Collection
    {
        $disallowedKey = $this->disallowedKeyword();
        $usedKey = Url::pluck('keyword')->toArray();

        return $disallowedKey->intersect($usedKey);
    }

    /**
     * Get all route paths that could conflict with generated keywords.
     *
     * Extracts URIs from registered routes and filters them to match the keyword
     * format. Prevents generating keywords that match existing routes.
     */
    public function routeCollisionList(): array
    {
        return collect(\Illuminate\Support\Facades\Route::getRoutes()->get())
            ->map(fn(\Illuminate\Routing\Route $route) => $route->uri)
            ->pipe(fn($paths) => $this->filterCollisionCandidates($paths))
            ->toArray();
    }

    /**
     * Get all file/folder names in the public directory that could conflict
     * with generated keywords.
     *
     * Scans the public directory and filters results to match the keyword format.
     * Prevents generating keywords that match existing files or folders.
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
     * Filter strings that match the allowed keyword format.
     */
    public function filterCollisionCandidates(array|Collection $value): Collection
    {
        return collect($value)
            ->filter(fn($value) => Preg::isMatch('/^([0-9a-zA-Z\-])+$/', $value))
            ->reject(fn($value) => in_array($value, self::RESERVED_KEYWORD))
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
     * Calculates the estimated impact of disallowed keywords on the total keyspace,
     * considering case variants.
     *
     * This weighting estimates how many potential keywords are effectively
     * unavailable because a single disallowed keyword must be avoided in all its
     * potential case variants. The result represents the estimated portion of
     * the keyspace considered occupied by these disallowed keywords.
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
