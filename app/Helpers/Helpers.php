<?php

use App\Helpers\HtmlHlp;
use App\Helpers\UrlHlp;
use CodeItNow\BarcodeBundle\Utils\QrCode;

/*
 * URL Helpers
 */
 if (!function_exists('url_limit')) {
     /**
      * @param string $url
      * @param int    $int
      *
      * @return string
      */
     function url_limit($url, $int = 50)
     {
         return resolve(UrlHlp::class)->url_limit($url, $int);
     }
 }

 if (!function_exists('url_normalize')) {
     /**
      * @param string $value
      *
      * @return string
      */
     function url_normalize($value)
     {
         return resolve(UrlHlp::class)->url_normalize($value);
     }
 }

/*
 * HTML Helpers
 */
if (!function_exists('style')) {
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

if (!function_exists('script')) {
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
if (!function_exists('qrCodeGenerator')) {
    function qrCodeGenerator($value)
    {
        $qrCode = new QrCode();
        $qrCode
            ->setText(url('/', $value))
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
