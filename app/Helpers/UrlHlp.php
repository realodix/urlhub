<?php

namespace App\Helpers;

class UrlHlp
{
    /**
     * @param string $url
     * @param int    $maxlength
     * @return string
     */
    public function url_limit($url, $maxlength)
    {
        $int_a = $maxlength * 0.6;
        $int_b = ($maxlength * 0.4 * -1) + 3; // + 3 dots from str_limit()

        if (strlen($url) > $maxlength) {
            return str_limit($url, $int_a).substr($url, $int_b);
        }

        return $url;
    }

    /**
     * @param string $value
     * @return string
     */
    public function remove_schemes($value)
    {
        return str_replace([
                    'http://',
                    'https://',
                    'www.',
               ], '', $value);
    }
}
