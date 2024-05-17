<?php

namespace Tests\Unit\Services;

use App\Services\QrCodeService;
use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

#[PHPUnit\Group('services')]
class QrCodeServiceTest extends TestCase
{
    private function getQrCode(): QrCodeService
    {
        return app(QrCodeService::class);
    }

    #[PHPUnit\Test]
    public function QrCodeService(): void
    {
        $QrCode = $this->getQrCode()->execute('foo');

        $this->assertInstanceOf(\Endroid\QrCode\Writer\Result\ResultInterface::class, $QrCode);
    }

    #[PHPUnit\Test]
    public function sizeMin(): void
    {
        $size = QrCodeService::MIN_SIZE - 1;
        config(['urlhub.qrcode_size' => $size]);

        $image = imagecreatefromstring($this->getQrCode()->execute('foo')->getString());

        $this->assertNotSame($size, (int) imagesx($image));
        $this->assertSame(QrCodeService::MIN_SIZE, imagesx($image));
    }

    #[PHPUnit\Test]
    public function sizeMax(): void
    {
        $size = QrCodeService::MAX_SIZE + 1;
        config(['urlhub.qrcode_size' => $size]);

        $image = imagecreatefromstring($this->getQrCode()->execute('foo')->getString());

        $this->assertNotSame($size, imagesx($image));
        $this->assertSame(QrCodeService::MAX_SIZE, imagesx($image));
    }

    #[PHPUnit\Test]
    public function resolveSize(): void
    {
        $size = $this->getQrCode()->resolveSize();
        $this->assertGreaterThanOrEqual(QrCodeService::MIN_SIZE, $size);
        $this->assertLessThanOrEqual(QrCodeService::MAX_SIZE, $size);
    }

    #[PHPUnit\Test]
    public function resolveMargin(): void
    {
        config(['urlhub.qrcode_margin' => -1]);
        $this->assertSame(0, $this->getQrCode()->resolveMargin());

        config(['urlhub.qrcode_margin' => 0]);
        $this->assertSame(0, $this->getQrCode()->resolveMargin());

        config(['urlhub.qrcode_margin' => 1]);
        $this->assertSame(1, $this->getQrCode()->resolveMargin());
    }

    #[PHPUnit\Test]
    public function resolveWriter(): void
    {
        config(['urlhub.qrcode_format' => 'svg']);
        $this->assertInstanceOf(
            \Endroid\QrCode\Writer\SvgWriter::class,
            $this->getQrCode()->resolveWriter()
        );

        config(['urlhub.qrcode_format' => 'png']);
        $this->assertInstanceOf(
            \Endroid\QrCode\Writer\PngWriter::class,
            $this->getQrCode()->resolveWriter()
        );
    }

    #[PHPUnit\Test]
    public function resolveErrorCorrection(): void
    {
        config(['urlhub.qrcode_error_correction' => 'l']);
        $this->assertSame(
            \Endroid\QrCode\ErrorCorrectionLevel::Low,
            $this->getQrCode()->resolveErrorCorrection()
        );

        config(['urlhub.qrcode_error_correction' => 'm']);
        $this->assertSame(
            \Endroid\QrCode\ErrorCorrectionLevel::Medium,
            $this->getQrCode()->resolveErrorCorrection()
        );

        config(['urlhub.qrcode_error_correction' => 'q']);
        $this->assertSame(
            \Endroid\QrCode\ErrorCorrectionLevel::Quartile,
            $this->getQrCode()->resolveErrorCorrection()
        );

        config(['urlhub.qrcode_error_correction' => 'h']);
        $this->assertSame(
            \Endroid\QrCode\ErrorCorrectionLevel::High,
            $this->getQrCode()->resolveErrorCorrection()
        );
    }

    #[PHPUnit\Test]
    public function resolveRoundBlockSize(): void
    {
        config(['urlhub.qrcode_round_block_size' => true]);
        $this->assertSame(
            \Endroid\QrCode\RoundBlockSizeMode::Margin,
            $this->getQrCode()->resolveRoundBlockSize()
        );

        config(['urlhub.qrcode_round_block_size' => false]);
        $this->assertSame(
            \Endroid\QrCode\RoundBlockSizeMode::None,
            $this->getQrCode()->resolveRoundBlockSize()
        );
    }
}
