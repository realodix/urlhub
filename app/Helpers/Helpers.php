<?php

use App\Helpers\HtmlHlp;
use App\Helpers\UrlHlp;
use CodeItNow\BarcodeBundle\Utils\QrCode;

/*
 * URL Helpers
 */
if (!function_exists('url_limit')) {
    function url_limit($url, $int = 50)
    {
        return resolve(UrlHlp::class)->url_limit($url, $int);
    }
}

if (!function_exists('url_normalize')) {
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

// https://gist.github.com/RadGH/84edff0cc81e6326029c
// https://github.com/sandervanhooft/laravel-blade-readable-numbers
if (!function_exists('readable_int')) {
    /**
     * Convert large positive numbers in to short form like 1K+, 100K+, 199K+, 1M+, 10M+, 1B+ etc.
     *
     * @param   $n
     *
     * @return string
     */
    function readable_int($n)
    {
        if ($n >= 0 && $n < 1000) {
            // 1 - 999
            $n_format = floor($n);
            $suffix = '';
        } elseif ($n >= 1000 && $n < 1000000) {
            // 1k-999k
            $n_format = floor($n / 1000);
            $suffix = 'K+';
        } elseif ($n >= 1000000 && $n < 1000000000) {
            // 1m-999m
            $n_format = floor($n / 1000000);
            $suffix = 'M+';
        } elseif ($n >= 1000000000 && $n < 1000000000000) {
            // 1b-999b
            $n_format = floor($n / 1000000000);
            $suffix = 'B+';
        } elseif ($n >= 1000000000000) {
            // 1t+
            $n_format = floor($n / 1000000000000);
            $suffix = 'T+';
        }

        return !empty($n_format.$suffix) ? $n_format.$suffix : 0;
    }
}
