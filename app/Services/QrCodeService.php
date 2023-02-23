<?php

namespace App\Services;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\Result\ResultInterface;

class QrCodeService
{
    const MIN_SIZE = 50;

    const MAX_SIZE = 1000;

    const FORMAT = 'png';

    const SUPPORTED_FORMAT = ['png', 'svg'];

    public function execute(string $data): ResultInterface
    {
        return Builder::create()
            ->data($data)
            ->labelText(__('Scan QR Code'))
            ->size($this->resolveSize())
            ->margin($this->resolveMargin())
            ->writer($this->resolveWriter())
            ->errorCorrectionLevel($this->resolveErrorCorrection())
            ->roundBlockSizeMode($this->resolveRoundBlockSize())
            ->build();
    }

    protected function resolveSize(): int
    {
        $size = config('urlhub.qrcode_size');

        if ($size < self::MIN_SIZE) {
            return self::MIN_SIZE;
        }

        return $size > self::MAX_SIZE ? self::MAX_SIZE : $size;
    }

    protected function resolveMargin(): int
    {
        $margin = config('urlhub.qrcode_margin');

        // if the margin is less than 0, set it to 0.
        // if the margin is greater than 0, set it to the margin.
        return $margin < 0 ? 0 : $margin;
    }

    /**
     * @return \Endroid\QrCode\Writer\WriterInterface
     */
    protected function resolveWriter()
    {
        $qFormat = self::normalizeValue(config('urlhub.qrcode_format'));
        $containSupportedFormat = collect(self::SUPPORTED_FORMAT)
            ->containsStrict($qFormat);
        $format = $containSupportedFormat ? $qFormat : self::FORMAT;

        return match ($format) {
            'svg' => new \Endroid\QrCode\Writer\SvgWriter,
            default => new \Endroid\QrCode\Writer\PngWriter,
        };
    }

    /**
     * @return \Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelInterface
     */
    protected function resolveErrorCorrection()
    {
        $level = self::normalizeValue(config('urlhub.qrcode_error_correction'));

        return match ($level) {
            'h' => new \Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh,
            'q' => new \Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelQuartile,
            'm' => new \Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelMedium,
            default => new \Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow, // 'l'
        };
    }

    /**
     * @return \Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeInterface
     */
    protected function resolveRoundBlockSize()
    {
        $isRounded = config('urlhub.qrcode_round_block_size');

        if (! $isRounded) {
            return new \Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeNone;
        }

        return new \Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
    }

    protected function normalizeValue(string $param): string
    {
        return strtolower(trim($param));
    }
}
