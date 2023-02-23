<?php

namespace Tests\Unit\Services;

use App\Services\QrCodeService;
use Endroid\QrCode\Writer\Result\ResultInterface;
use Tests\TestCase;

class QrCodeServiceTest extends TestCase
{
    private function getQrCode(): QrCodeService
    {
        return app(QrCodeService::class);
    }

    /**
     * @test
     * @group u-actions
     */
    public function QrCodeService(): void
    {
        $QrCode = $this->getQrCode()->execute('foo');

        $this->assertInstanceOf(ResultInterface::class, $QrCode);
    }

    /**
     * @test
     * @group u-actions
     */
    public function sizeMin(): void
    {
        $size = QrCodeService::MIN_SIZE - 1;
        config(['urlhub.qrcode_size' => $size]);

        $image = imagecreatefromstring($this->getQrCode()->execute('foo')->getString());

        $this->assertNotSame($size, (int) imagesx($image));
        $this->assertSame(QrCodeService::MIN_SIZE, imagesx($image));
    }

    /**
     * @test
     * @group u-actions
     */
    public function sizeMax(): void
    {
        $size = QrCodeService::MAX_SIZE + 1;
        config(['urlhub.qrcode_size' => $size]);

        $image = imagecreatefromstring($this->getQrCode()->execute('foo')->getString());

        $this->assertNotSame($size, imagesx($image));
        $this->assertSame(QrCodeService::MAX_SIZE, imagesx($image));
    }

    /**
     * resolveRoundBlockSize() should return \Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeNone
     * if config('urlhub.qrcode_round_block_size') set `false`.
     *
     * @test
     */
    public function resolveRoundBlockSizeShouldReturnRoundBlockSizeModeNone(): void
    {
        config(['urlhub.qrcode_round_block_size' => false]);

        $QrCode = $this->getQrCode();

        $reflection = new \ReflectionClass($QrCode);
        $method = $reflection->getMethod('resolveRoundBlockSize');
        $method->setAccessible(true);

        $this->assertInstanceOf(
            \Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeNone::class,
            $method->invoke($QrCode)
        );
    }
}
