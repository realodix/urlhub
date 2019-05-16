<?php

namespace App\Services;

use App\Url;
use Hidehalo\Nanoid\Client;

/**
 * Useful functions to use in the Whole App for Short URLs.
 */
class UrlService
{
    /**
     * Generate an unique short URL using Nanoid.
     *
     * @return string
     */
    public function key_generator()
    {
        $generateId = new Client();
        $alphabet = config('urlhub.hash_alphabet');
        $size1 = (int) config('urlhub.hash_size_1');
        $size2 = (int) config('urlhub.hash_size_2');

        if (($size1 == $size2) || $size2 == 0) {
            $size2 = $size1;
        }

        $urlKey = $generateId->formatedId($alphabet, $size1);

        // If it is already used (not available), find the next available
        // ending.
        $link = Url::whereUrlKey($urlKey)->first();

        while ($link) {
            $urlKey = $generateId->formatedId($alphabet, $size2);
            $link = Url::whereUrlKey($urlKey)->first();
        }

        return $urlKey;
    }

    /**
     * @return int
     */
    public function url_key_capacity()
    {
        $alphabet = strlen(config('urlhub.hash_alphabet'));
        $size1 = (int) config('urlhub.hash_size_1');
        $size2 = (int) config('urlhub.hash_size_2');

        // If the hash size is filled with integers that do not match the rules,
        // change the variable's value to 0.
        $size1 = ! ($size1 < 1) ? $size1 : 0;
        $size2 = ! ($size2 < 0) ? $size2 : 0;

        if ($size1 == 0 || ($size1 == 0 && $size2 == 0)) {
            return 0;
        } elseif ($size1 == $size2 || $size2 == 0) {
            return pow($alphabet, $size1);
        } else {
            return pow($alphabet, $size1) + pow($alphabet, $size2);
        }
    }

    /**
     * @return int
     */
    public function url_key_remaining()
    {
        $totalShortUrl = Url::whereIsCustom(false)->count();

        if ($this->url_key_capacity() < $totalShortUrl) {
            return 0;
        }

        return $this->url_key_capacity() - $totalShortUrl;
    }
}
