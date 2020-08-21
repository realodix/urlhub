<?php

use CodeItNow\BarcodeBundle\Utils\QrCode;
use Illuminate\Support\Str;
use Spatie\Url\Url as SpatieUrl;

if (! function_exists('uHub')) {
    /**
     * Helper that makes the way to access the configuration value in
     * '/config/urlhub.php' becomes easier.
     *
     * Example:
     * - uHub('option') is equal to config('urlhub.option').
     *
     * @codeCoverageIgnore
     * @param string $value
     *
     * @return mixed
     */
    function uHub(string $value)
    {
        // Validation of character types allowed in the `urlhub.hash_char`
        // configuration option
        return config('urlhub.'.$value);
    }
}

if (! function_exists('appName')) {
    /**
     * Helper to grab the application name.
     *
     * @return mixed
     */
    function appName()
    {
        return config('app.name');
    }
}

if (! function_exists('urlDisplay')) {
    /**
     * Display the link according to what You need.
     *
     * @param string $url    URL or Link.
     * @param bool   $scheme Show or remove URL schemes.
     * @param int    $limit  Length string will be truncated to, including
     *                       suffix.
     * @return string
     */
    function urlDisplay(string $url, bool $scheme = true, int $limit = null)
    {
        $sUrl = SpatieUrl::fromString($url);
        $hostLen = strlen($sUrl->getScheme().'://'.$sUrl->getHost());
        $urlLen = strlen($url);
        $limit = is_null($limit) ? $urlLen : $limit;

        // Remove URL schemes
        if (! $scheme) {
            $url = urlRemoveScheme($url);
            $hostLen = strlen($sUrl->getHost());
        }

        $pathLen = $limit - $hostLen;

        // If it's only the host and has the trailing slash at the end, then
        // remove the trailing slash.
        if ($pathLen === 1) {
            $url = rtrim($url, '/\\');
        }

        if ($urlLen > $limit) {
            // The length of string truncated by Str::limit() does not include
            // a suffix, so it needs to be adjusted so that the length of the
            // truncated string matches the expected limit.
            $adjLimit = $limit - (strlen(Str::limit($url, $limit)) - $limit);

            $firstSide = $hostLen + intval(($pathLen - 1) * 0.5);
            $lastSide = -abs($adjLimit - $firstSide);

            if (((1 <= $pathLen) && ($pathLen <= 9)) || ($hostLen > $limit)) {
                return Str::limit($url, $adjLimit);
            }

            return Str::limit($url, $firstSide).substr($url, $lastSide);
        }

        return $url;
    }
}

if (! function_exists('urlRemoveScheme')) {
    /**
     * @param string $value
     * @return string
     */
    function urlRemoveScheme($value)
    {
        return str_replace([
            'http://',
            'https://',
            'www.',
        ], '', $value);
    }
}

if (! function_exists('qrCode')) {
    /**
     * Barcode & QrCode Generator.
     *
     * @codeCoverageIgnore
     * @param string $string
     *
     * @return \CodeItNow\BarcodeBundle\Utils\QrCode
     */
    function qrCode($string)
    {
        $qrCode = new QrCode();
        $qrCode->setText($string)
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
