<?php

namespace Tests\Unit\Services;

use App\Services\QrCodeService;
use PHPUnit\Framework\Attributes\{Group, Test};
use Tests\TestCase;

class QrCodeServiceTest extends TestCase
{
    private function getQrCode(): QrCodeService
    {
        return app(QrCodeService::class);
    }

    #[Test]
    #[Group('u-actions')]
    public function QrCodeService(): void
    {
        $QrCode = $this->getQrCode()->execute('foo');

        $this->assertInstanceOf(\Endroid\QrCode\Writer\Result\ResultInterface::class, $QrCode);
    }

    #[Test]
    #[Group('u-actions')]
    public function sizeMin(): void
    {
        $size = QrCodeService::MIN_SIZE - 1;
        config(['urlhub.qrcode_size' => $size]);

        $image = imagecreatefromstring($this->getQrCode()->execute('foo')->getString());

        $this->assertNotSame($size, (int) imagesx($image));
        $this->assertSame(QrCodeService::MIN_SIZE, imagesx($image));
    }

    #[Test]
    #[Group('u-actions')]
    public function sizeMax(): void
    {
        $size = QrCodeService::MAX_SIZE + 1;
        config(['urlhub.qrcode_size' => $size]);

        $image = imagecreatefromstring($this->getQrCode()->execute('foo')->getString());

        $this->assertNotSame($size, imagesx($image));
        $this->assertSame(QrCodeService::MAX_SIZE, imagesx($image));
    }

    #[Test]
    #[Group('u-actions')]
    public function resolveSize(): void
    {
        $size = $this->getQrCode()->resolveSize();
        $this->assertGreaterThanOrEqual(QrCodeService::MIN_SIZE, $size);
        $this->assertLessThanOrEqual(QrCodeService::MAX_SIZE, $size);
    }

    #[Test]
    #[Group('u-actions')]
    public function resolveMargin(): void
    {
        config(['urlhub.qrcode_margin' => -1]);
        $this->assertSame(0, $this->getQrCode()->resolveMargin());

        config(['urlhub.qrcode_margin' => 0]);
        $this->assertSame(0, $this->getQrCode()->resolveMargin());

        config(['urlhub.qrcode_margin' => 1]);
        $this->assertSame(1, $this->getQrCode()->resolveMargin());
    }

    #[Test]
    #[Group('u-actions')]
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

    #[Test]
    #[Group('u-actions')]
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

    #[Test]
    #[Group('u-actions')]
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
