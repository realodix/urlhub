<?php

namespace App\Helpers;

use App\Url;
use Hidehalo\Nanoid\Client;

class UrlHlp
{
    /**
     * @return string
     */
    public function url_generator()
    {
        $generateId = new Client();
        $alphabet = config('plur.hash_alphabet');
        $size1 = config('plur.hash_size_1');
        $size2 = config('plur.hash_size_2');

        $shortURL = $generateId->formatedId($alphabet, $size1);

        // If it is already used (not available),
        // find the next available base64 ending.
        $link = Url::where('short_url', $shortURL)->first();

        if (($size1 == $size2) || $size2 == 0) {
            $size2 = $size1;
        }

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

        $title = preg_match('/<title[^>]*>(.*?)<\/title>/ims', $data, $matches) ? $matches[1] : __('No Title');

        return $title;
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
        if (str_contains($value, 'http://')) {
            $value = str_replace_first('http://', '', $value);
        }

        if (str_contains($value, 'https://')) {
            $value = str_replace_first('https://', '', $value);
        }

        if (str_contains($value, 'www.')) {
            $value = str_replace_first('www.', '', $value);
        }

        return $value;
    }
}
