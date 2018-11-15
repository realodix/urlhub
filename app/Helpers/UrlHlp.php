<?php

namespace App\Helpers;

use App\Url;
use Hidehalo\Nanoid\Client;

class UrlHlp
{
    /**
     * @return string
     */
    public function key_generator()
    {
        $generateId = new Client();
        $alphabet = config('plur.hash_alphabet');
        $size1 = config('plur.hash_size_1');
        $size2 = config('plur.hash_size_2');

        if (($size1 == $size2) || $size2 == 0) {
            $size2 = $size1;
        }

        $urlKey = $generateId->formatedId($alphabet, $size1);

        // If it is already used (not available),
        // find the next available ending.
        $link = Url::whereUrlKey($urlKey)->first();

        while ($link) {
            $urlKey = $generateId->formatedId($alphabet, $size2);
            $link = Url::whereUrlKey($urlKey)->first();
        }

        return $urlKey;
    }

    /**
     * @param string $value
     * @return string
     */
    public function getTitle($value)
    {
        $data = @file_get_contents($value);

        $title = preg_match('/<title[^>]*>(.*?)<\/title>/ims', $data, $matches);

        if ($title) {
            $title = $matches[1];
        } else {
            $title = title_case($this->getDomain($value)).' - '.__('No Title');

            if (! $this->getDomain($value)) {
                $title = __('No Title');
            }
        }

        return $title;
    }

    /**
     * @param string $value
     * @return mixed
     */
    // https://stackoverflow.com/a/399316
    public function getDomain($value)
    {
        $pieces = parse_url($value);
        $domain = isset($pieces['host']) ? $pieces['host'] : '';

        if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
            return $regs['domain'];
        }

        return false;
    }

    /**
     * @param string $value
     * @param int    $int
     * @return string
     */
    public function url_limit($value, $int = 50)
    {
        $int_a = (60 / 100) * $int;
        $int_b = ($int - $int_a) * -1;

        if (strlen($value) > $int) {
            return str_limit($value, $int_a).substr($value, $int_b);
        }

        return $value;
    }

    /**
     * @param string $value
     * @return string
     */
    public function remove_url_schemes($value)
    {
        return str_replace([
                    'http://',
                    'https://',
                    'www.',
                ], '', $value);
    }

    /**
     * @return int
     */
    public function url_key_capacity()
    {
        $alphabet = strlen(config('plur.hash_alphabet'));
        $size1 = config('plur.hash_size_1');
        $size2 = config('plur.hash_size_2');

        $capacity = pow($alphabet, $size1) + pow($alphabet, $size2);

        if (($size1 == $size2) || $size2 == 0) {
            $capacity = pow($alphabet, $size1);
        }

        return $capacity;
    }

    /**
     * @return int
     */
    public function url_key_remaining()
    {
        $totalShortUrl = Url::where('is_custom', 0)->count();

        return $this->url_key_capacity() - $totalShortUrl;
    }
}
