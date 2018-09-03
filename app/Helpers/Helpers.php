<?php

use App\Helpers\HtmlHlp;
use App\Url;
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
         $int_a = (60 / 100) * $int;
         $int_b = ($int - $int_a) * -1;

         if (strlen($url) > $int) {
             $s_url = str_limit($url, $int_a).substr($url, $int_b);

             return $s_url;
         }

         return $url;
     }
 }

 if (!function_exists('urlToDomain')) {
     /**
      * @param string $value
      *
      * @return string
      */
     function urlToDomain($value)
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
