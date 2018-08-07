<?php

namespace App\Helpers;

use App\Url;
use Hashids\Hashids;

class UrlHlp
{
    public function url_generator()
    {
        $getUrlIdInDB = Url::orderBy('id', 'desc')->limit(1)->first();

        $hashids = new Hashids('', 6);

        if (empty($getUrlIdInDB)) {
            $shortURL = $hashids->encode(1);
        } else {
            $shortURL = $hashids->encode($getUrlIdInDB->id + 1);
        }

        return $shortURL;
    }

    public function get_title($url)
    {
        $data = @file_get_contents($url);

        $title = preg_match('/<title[^>]*>(.*?)<\/title>/ims', $data, $matches) ? $matches[1] : $url;

        return $title;
    }

    public function url_limit($url, $int)
    {
        $int_a = (60 / 100) * $int;
        $int_b = ($int - $int_a) * -1;

        if (strlen($url) > $int) {
            $s_url = str_limit($url, $int_a) . substr($url, $int_b);

            return $s_url;
        }

        return $url;
    }

    public function urlToDomain($str)
    {
        if (str_contains($str, 'http://')) {
            $str = str_replace_first('http://', '', $str);
        }

        if (str_contains($str, 'https://')) {
            $str = str_replace_first('https://', '', $str);
        }

        if (str_contains($str, 'www.')) {
            $str = str_replace_first('www.', '', $str);
        }

        return $str;
    }
}
