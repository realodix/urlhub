<?php

namespace App\Services;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\{PngWriter, SvgWriter};
use Endroid\QrCode\{ErrorCorrectionLevel, RoundBlockSizeMode};

class QrCodeService
{
    const MIN_SIZE = 50;

    const MAX_SIZE = 1000;

    const FORMAT = 'png';

    const SUPPORTED_FORMAT = ['png', 'svg'];

    /**
     * @return \Endroid\QrCode\Writer\Result\ResultInterface
     */
    public function execute(string $data)
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

    public function resolveSize(): int
    {
        $size = config('urlhub.qrcode_size');

        if ($size < self::MIN_SIZE) {
            return self::MIN_SIZE;
        }

        return $size > self::MAX_SIZE ? self::MAX_SIZE : $size;
    }

    public function resolveMargin(): int
    {
        $margin = config('urlhub.qrcode_margin');

        // if the margin is less than 0, set it to 0.
        // if the margin is greater than 0, set it to the margin.
        return $margin < 0 ? 0 : $margin;
    }

    /**
     * @return \Endroid\QrCode\Writer\WriterInterface
     */
    public function resolveWriter()
    {
        $qFormat = self::normalizeValue(config('urlhub.qrcode_format'));
        $containSupportedFormat = collect(self::SUPPORTED_FORMAT)
            ->containsStrict($qFormat);
        $format = $containSupportedFormat ? $qFormat : self::FORMAT;

        return match ($format) {
            'svg' => new SvgWriter,
            default => new PngWriter,
        };
    }

    /**
     * @return ErrorCorrectionLevel \Endroid\QrCode\ErrorCorrectionLevel
     */
    public function resolveErrorCorrection()
    {
        $level = self::normalizeValue(config('urlhub.qrcode_error_correction'));

        return match ($level) {
            'h' => ErrorCorrectionLevel::High,
            'q' => ErrorCorrectionLevel::Quartile,
            'm' => ErrorCorrectionLevel::Medium,
            default => ErrorCorrectionLevel::Low, // 'l'
        };
    }

    /**
     * @return RoundBlockSizeMode \Endroid\QrCode\RoundBlockSizeMode
     */
    public function resolveRoundBlockSize()
    {
        $isRounded = config('urlhub.qrcode_round_block_size');

        if (! $isRounded) {
            return RoundBlockSizeMode::None;
        }

        return RoundBlockSizeMode::Margin;
    }

    public function normalizeValue(string $param): string
    {
        return strtolower(trim($param));
    }
}
