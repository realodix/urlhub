<?php

namespace App\Helpers;

use CodeItNow\BarcodeBundle\Utils\QrCode;

class Hlp
{
    /**
     * @param  mixed $value
     * @return mixed
     */
    public function qrCodeGenerator($value)
    {
        $qrCode = new QrCode();
        $qrCode
            ->setText(url('/', $value))
            ->setSize(150)
            ->setPadding(10)
            ->setErrorCorrection('high')
            ->setForegroundColor(array('r' => 0, 'g' => 0, 'b' => 0, 'a' => 0))
            ->setBackgroundColor(array('r' => 255, 'g' => 255, 'b' => 255, 'a' => 0))
            ->setLabel('Scan QR Code')
            ->setLabelFontSize(12)
            ->setImageType(QrCode::IMAGE_TYPE_PNG)
        ;

        return $qrCode;
    }
}
