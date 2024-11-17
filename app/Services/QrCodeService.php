<?php

namespace App\Services;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;

class QrCodeService
{
    /**
     * @return \Endroid\QrCode\Writer\Result\ResultInterface
     */
    public function execute(string $data)
    {
        $builder = new Builder(
            data: $data,
            labelText: __('Scan QR Code'),
            size: 170,
            margin: 0,
            writer: new \Endroid\QrCode\Writer\PngWriter,
            errorCorrectionLevel: ErrorCorrectionLevel::Low,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
        );

        return $builder->build();
    }
}
