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

        if ($data == true) {
            $title = preg_match('/<title[^>]*>(.*?)<\/title>/ims', $data, $matches) ? $matches[1] : null;
        } else {
            $title = $url;
        }

        return $title;
    }

    public function url_limit($str)
    {
        if (strlen($str) > 50) {
            $s_url = str_limit($str, 30) . substr($str, -20);

            return $this->urlToDomain($s_url);
        }

        return $this->urlToDomain($str);
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
