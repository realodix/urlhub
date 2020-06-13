<?php

use CodeItNow\BarcodeBundle\Utils\QrCode;

if (! function_exists('qrCodeGenerator')) {
    /**
     * @codeCoverageIgnore
     * @return string
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
