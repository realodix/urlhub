<?php

namespace App\Helpers;

use CodeItNow\BarcodeBundle\Utils\QrCode;

class Hlp
{
    public function qrCodeGenerator($url)
    {
        $qrCode = new QrCode();
        $qrCode
            ->setText(url('/', $url))
            ->setSize(150)
            ->setPadding(10)
            ->setErrorCorrection('high')
            ->setForegroundColor(array('r' => 0, 'g' => 0, 'b' => 0, 'a' => 0))
            ->setBackgroundColor(array('r' => 255, 'g' => 255, 'b' => 255, 'a' => 0))
            ->setLabel('Scan Qr Code')
            ->setLabelFontSize(12)
            ->setImageType(QrCode::IMAGE_TYPE_PNG)
        ;

        return $qrCode;
    }
}
