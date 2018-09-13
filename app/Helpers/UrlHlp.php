<?php

namespace App\Helpers;

use App\Url;
use Hashids\Hashids;

class UrlHlp
{
    /**
     * @return string
     */
    public function url_generator()
    {
        $getUrlIdInDB = Url::latest()->first();

        $hashids = new Hashids('', 6);

        if (empty($getUrlIdInDB)) {
            $shortURL = $hashids->encode(1);
        } else {
            $shortURL = $hashids->encode($getUrlIdInDB->id + 1);
        }

        return $shortURL;
    }

    /**
     * @param string $value
     *
     * @return string
     */
    public function get_title($value)
    {
        $data = @file_get_contents($value);

        $title = preg_match('/<title[^>]*>(.*?)<\/title>/ims', $data, $matches) ? $matches[1] : $value;

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
    public function urlToDomain($value)
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

    // /**
    //  * @param  string $value
    //  * @return boolean
    //  */
    // public function domainBlocked($value)
    // {
    //     $blockedDomainList = [
    //         'adf.ly',
    //         'bit.ly',
    //         'clc.la', 'clc.to',
    //         'goo.gl',
    //         'is.gd',
    //         'j.mp',
    //         'ow.ly',
    //         'polr.me',
    //         's.id',
    //         'shorturl.at',
    //         't.co',
    //         'tiny.cc', 'tinyurl.com',
    //         'ur1.ca',
    //         'v.ht',
    //     ];
    //
    //     $contains = str_contains($value, $blockedDomainList);
    //
    //     return $contains;
    // }
}
