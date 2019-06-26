<?php

use App\Helpers\HtmlHlp;
use App\Helpers\NumHlp;
use App\Helpers\UrlHlp;
use CodeItNow\BarcodeBundle\Utils\QrCode;
use GeoIp2\Database\Reader;

/*
|--------------------------------------------------------------------------
| Global Helpers
|--------------------------------------------------------------------------
|
| Here is where you can register helper functions for your application.
| These helper functions can be uesed anywhere in you application
| Now create something great!
|
*/

/*
 * Num Helpers
 */
if (! function_exists('readable_int')) {
    function readable_int($value)
    {
        return resolve(NumHlp::class)->readable_int($value);
    }
}

/*
 * URL Helpers
 */
if (! function_exists('url_limit')) {
    function url_limit($url, $maxlength = 50)
    {
        return resolve(UrlHlp::class)->url_limit($url, $maxlength);
    }
}

if (! function_exists('remove_schemes')) {
    function remove_schemes($value)
    {
        return resolve(UrlHlp::class)->remove_schemes($value);
    }
}

/*
 * HTML Helpers
 */
if (! function_exists('style')) {
    /**
     * @param       $url
     * @param array $attributes
     * @param null  $secure
     *
     * @return mixed
     */
    function style($url, $attributes = [], $secure = null)
    {
        return resolve(HtmlHlp::class)->style($url, $attributes, $secure);
    }
}

if (! function_exists('script')) {
    /**
     * @param       $url
     * @param array $attributes
     * @param null  $secure
     *
     * @return mixed
     */
    function script($url, $attributes = [], $secure = null)
    {
        return resolve(HtmlHlp::class)->script($url, $attributes, $secure);
    }
}

/*
 *
 */

if (! function_exists('qrCodeGenerator')) {
    /**
     * @codeCoverageIgnore
     */
    function qrCodeGenerator($value)
    {
        $qrCode = new QrCode();
        $qrCode->setText($value)
               ->setSize(150)
               ->setPadding(10)
               ->setErrorCorrection('high')
               ->setForegroundColor(['r' => 0, 'g' => 0, 'b' => 0, 'a' => 0])
               ->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255, 'a' => 0])
               ->setLabel('Scan QR Code')
               ->setLabelFontSize(12)
               ->setImageType(QrCode::IMAGE_TYPE_PNG);

        return $qrCode;
    }
}

if (! function_exists('getCountries')) {
    /**
     * We try to get the IP country. If it fails, because GeoLite2 doesn't know
     * the IP country, we will set it to Unknown
     */
    function getCountries($ip)
    {
        try {
            // @codeCoverageIgnoreStart
            $reader = new Reader(database_path().'/GeoLite2-Country.mmdb');
            $record = $reader->country($ip);
            $countryCode = $record->country->isoCode;
            $countryName = $record->country->name;

            return compact('countryCode', 'countryName');
            // @codeCoverageIgnoreEnd
        } catch (\Exception $e) {
            $countryCode = 'N/A';
            $countryName = 'Unknown';

            return compact('countryCode', 'countryName');
        }
    }
}
