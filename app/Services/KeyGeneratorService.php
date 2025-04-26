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
     * The capacity of the URL shortener is the number of unique strings that can
     * be generated minus the number of reserved keywords.
     */
    public function capacity(): int
    {
        return $this->maxUniqueStrings() - $this->reservedKeywordWeight();
    }

    /**
     * Calculate the number of unique random strings that can still be generated.
     */
    public function remainingCapacity(): int
    {
        // max() is used to avoid negative values
        return max($this->capacity() - $this->keywordCount(), 0);
    }

    /**
     * Counts the number of valid keywords, where the keywords have a length equal
     * to the configured keyword length and contain only allowed characters.
     */
    public function keywordCount(): int
    {
        $length = $this->settings->key_len;

        return Url::whereRaw('LENGTH(keyword) = ?', [$length])
            ->when(\Illuminate\Support\Facades\DB::getDriverName() === 'sqlite',
                function (\Illuminate\Database\Eloquent\Builder $query): void {
                    $query->whereRaw("keyword NOT LIKE ? ESCAPE '\'", ['%\_%']);
                },
                function (\Illuminate\Database\Eloquent\Builder $query): void {
                    $query->whereNotLike('keyword', '%\\_%');
                },
            )
            ->count();
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

    public function reservedKeywordWeight(): int
    {
        $settings = app(GeneralSettings::class);

        return $this->reservedKeyword()
            ->filter(fn($value) => strlen($value) == $settings->key_len)
            ->count();
    }
}
