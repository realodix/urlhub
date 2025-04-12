<?php

namespace App\Actions;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;

class GenerateQrCode
{
    /**
     * @return \Endroid\QrCode\Writer\Result\ResultInterface
     */
    public function handle(string $data)
    {
        $builder = new Builder(
            data: $data,
            labelText: 'Scan QR Code',
            size: 170,
            margin: 0,
            writer: new \Endroid\QrCode\Writer\PngWriter,
            errorCorrectionLevel: ErrorCorrectionLevel::Low,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
        );

        return $builder->build();
    }
}
