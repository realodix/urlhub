<?php

namespace Tests\Unit\Actions;

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
    public function QrCodeService()
    {
        $QrCode = $this->getQrCode()->execute('foo');

        $this->assertInstanceOf(ResultInterface::class, $QrCode);
    }

    /**
     * @test
     * @group u-actions
     */
    public function sizeMin()
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
    public function sizeMax()
    {
        $size = QrCodeService::MAX_SIZE + 1;
        config(['urlhub.qrcode_size' => $size]);

        $image = imagecreatefromstring($this->getQrCode()->execute('foo')->getString());

        $this->assertNotSame($size, imagesx($image));
        $this->assertSame(QrCodeService::MAX_SIZE, imagesx($image));
    }
}
