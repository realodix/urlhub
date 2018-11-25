<?php

use App\Helpers\HtmlHlp;
use App\Helpers\NumHlp;
use App\Helpers\UrlHlp;
use CodeItNow\BarcodeBundle\Utils\QrCode;

/*
 * URL Helpers
 */
if (! function_exists('url_limit')) {
    function url_limit($url, $maxlength = 50)
    {
        return resolve(UrlHlp::class)->url_limit($url, $maxlength);
    }
}

if (! function_exists('remove_url_schemes')) {
    function remove_url_schemes($value)
    {
        return resolve(UrlHlp::class)->remove_url_schemes($value);
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

if (! function_exists('readable_int')) {
    function readable_int($value)
    {
        return resolve(NumHlp::class)->readable_int($value);
    }
}
