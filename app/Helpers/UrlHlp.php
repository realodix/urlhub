<?php

namespace App\Helpers;

use App\Url;
use Hidehalo\Nanoid\Client;

class UrlHlp
{
    /**
     * @return string
     */
    public function link_generator()
    {
        $generateId = new Client();
        $alphabet = config('plur.hash_alphabet');
        $size1 = config('plur.hash_size_1');
        $size2 = config('plur.hash_size_2');

        if (($size1 == $size2) || $size2 == 0) {
            $size2 = $size1;
        }

        $shortURL = $generateId->formatedId($alphabet, $size1);

        // If it is already used (not available),
        // find the next available ending.
        $link = Url::where('short_url', $shortURL)->first();

        while ($link) {
            $shortURL = $generateId->formatedId($alphabet, $size2);
            $link = Url::where('short_url', $shortURL)->first();
        }

        return $shortURL;
    }

    /**
     * @param string $value
     *
     * @return string
     */
    public function url_get_title($value)
    {
        $data = @file_get_contents($value);

        $title = preg_match('/<title[^>]*>(.*?)<\/title>/ims', $data, $matches);

        if ($title) {
            $title = $matches[1];
        } else {
            $title = title_case($this->url_get_domain($value)).' - '.__('No Title');

            if (!$this->url_get_domain($value)) {
                $title = __('No Title');
            }
        }

        return $title;
    }

    /**
     * @param string $url
     *
     * @return mixed
     */
    // https://stackoverflow.com/a/399316
    public function url_get_domain($url)
    {
        $pieces = parse_url($url);
        $domain = isset($pieces['host']) ? $pieces['host'] : '';

        if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
            return $regs['domain'];
        }

        return false;
    }

    /**
     * @param string $url
     * @param int    $int
     *
     * @return string
     */
    public function url_limit($url, $int = 50)
    {
        $int_a = (60 / 100) * $int;
        $int_b = ($int - $int_a) * -1;

        if (strlen($url) > $int) {
            $s_url = str_limit($url, $int_a).substr($url, $int_b);

            return $s_url;
        }

        return $url;
    }

    /**
     * @param string $value
     *
     * @return string
     */
    public function url_parsed($value)
    {
        return str_replace([
                    'http://',
                    'https://',
                    'www.',
                ], '', $value);
    }

    public function url_capacity()
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

    public function url_remaining()
    {
        $totalShortUrlCustom = Url::where('short_url_custom', '!=', '')->count();

        return ($this->url_capacity() + $totalShortUrlCustom) - Url::count('short_url');
    }
}
