<?php

namespace App\Helpers\General;

use Illuminate\Support\Str;
use Spatie\Url\Url;

class GeneralHelper
{
    /**
     * Helper that makes the way to access the configuration value in
     * '/config/urlhub.php' becomes easier.
     * @codeCoverageIgnore
     *
     * Example:
     * - uHub('option') is equal to config('urlhub.option').
     *
     * @param string $value
     * @return mixed
     */
    public function uHub($value)
    {
        // Validation of character types allowed in the `urlhub.hash_char`
        // configuration option
        return config('urlhub.'.$value);
    }

    /**
     * The strLimit method truncates the given string at the specified length.
     *
     * @param string $string
     * @param int    $maxlength
     * @return string
     */
    public function strLimit($string, $maxlength)
    {
        $int_a = $maxlength * 0.6;
        $int_b = ($maxlength * 0.4 * -1) + 3; // + 3 dots from Str::limit()

        if (strlen($string) > $maxlength) {
            return Str::limit($string, $int_a).substr($string, $int_b);
        }

        return $string;
    }

    /**
     * Helper function to display links as needed.
     *
     * @param string $longUrl URL or Link
     * @param int    $scheme  Show scheme or not
     * @param int    $length  The maximum length of a url or link. Fill with
     *                        0 if you want to display all of it.
     * @return string
     */
    public function urlDisplay($longUrl, $scheme, $length)
    {
        $url = $longUrl;
        $urlFS = Url::fromString($url);

        $urlScheme = $urlFS->getScheme().'://';
        $hostLength = strlen($urlFS->withHost($urlFS->getHost()));

        if ($scheme == false) {
            $url = $this->urlRemoveScheme($longUrl);
            $hostLength = strlen($urlFS->getHost());
        }

        if ($length == 0) {
            return $url;
        }

        $firstSide = $length * 0.6;
        $lastSide = (($length - $firstSide) * -1) + 3; // + 3 dots from Str::limit()

        if ($hostLength >= $firstSide){
            return Str::limit($url, $length);
        }

        if (strlen($url) > $length) {
            return Str::limit($url, $firstSide).substr($url, $lastSide);
        }

        return $url;
    }

    /**
     * @param string $value
     * @return string
     */
    public function urlRemoveScheme($value)
    {
        return str_replace([
            'http://',
            'https://',
            'www.',
        ], '', $value);
    }
}
// oploverznews.blogspot.com
// caramenangtaruhanbola.com
// downloadvideogratiss.blogspot.com
// filmindonesia2020.blogspot.com
// informations-library.blogspot.com
