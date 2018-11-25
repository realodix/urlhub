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
     * Gets the title of page from its url.
     * @param string $url
     * @return string
     */
    public function getTitle($url)
    {
        if ($title = preg_match('/<title[^>]*>(.*?)<\/title>/ims', @file_get_contents($url), $matches)) {
            return $matches[1];
        } elseif ($domain = $this->getDomain($url)) {
            return title_case($domain).' - '.__('No Title');
        } else {
            return __('No Title');
        }
    }

    /**
     * Extract the domain name using the classic parse_url() and then look
     * for a valid domain without any subdomain (www being a subdomain).
     * Won't work on things like 'localhost'. Will return false if it didn't
     * match anything.
     *
     * @param string $url
     * @return mixed
     */
    public function getDomain($url)
    {
        // https://stackoverflow.com/a/399316
        $pieces = parse_url($url);
        $domain = isset($pieces['host']) ? $pieces['host'] : '';

        if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
            return $regs['domain'];
        }

        return false;
    }

    /**
     * @param string $url
     * @param int    $maxlength
     * @return string
     */
    public function url_limit($url, $maxlength)
    {
        $int_a = $maxlength * 0.6;
        $int_b = $maxlength * 0.4 * -1;

        if (strlen($url) > $maxlength) {
            return str_limit($url, $int_a).substr($url, $int_b);
        }

        return $url;
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
