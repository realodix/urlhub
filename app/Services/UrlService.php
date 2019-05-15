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
}
