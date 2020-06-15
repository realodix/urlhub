<?php

use App\Helpers\General\GeneralHelper;
use CodeItNow\BarcodeBundle\Utils\QrCode;

if (! function_exists('uHub')) {
    /**
     * uHub('option') is equal to config('urlhub.option').
     *
     * @param string $value
     * @return mixed
     */
    function uHub($value)
    {
        return resolve(GeneralHelper::class)->uHub($value);
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

if (! function_exists('qrCode')) {
    /**
     * @codeCoverageIgnore
     * Barcode & QrCode Generator
     *
     * @return string
     */
    function qrCode($value)
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
