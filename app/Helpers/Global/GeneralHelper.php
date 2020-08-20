<?php

use CodeItNow\BarcodeBundle\Utils\QrCode;
use Illuminate\Support\Str;
use Spatie\Url\Url as SpatieUrl;

if (! function_exists('uHub')) {
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
    function uHub($value)
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
     * Display links or URLs as needed.
     *
     * @param string $url    URL or Link
     * @param bool   $scheme Show scheme or not
     * @param int    $length Truncates the given string at the specified length.
     *                       Set to 0 to display all of it.
     * @return string
     */
    function urlDisplay(string $url, bool $scheme = true, int $length = 0)
    {
        $urlFS = SpatieUrl::fromString($url);
        $hostLen = strlen($urlFS->getScheme().'://'.$urlFS->getHost());

        if ($scheme == false) {
            $url = urlRemoveScheme($url);
            $hostLen = strlen($urlFS->getHost());
        }

        if ($length <= 0) {
            return $url;
        }

        if ($hostLen >= 30 || (($hostLen <= 27) && ($length <= 30))) {
            $length -= 3;

            return Str::limit($url, $length);
        }

        $firstSide = intval($length * 0.6); // use intval to prevent float
        $lastSide = (($length - $firstSide) * -1) + 3; // + 3 dots from Str::limit()

        if (strlen($url) > $length) {
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
     * @codeCoverageIgnore
     *
     * @param string $string
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
