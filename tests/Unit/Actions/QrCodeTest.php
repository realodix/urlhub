<?php

namespace Tests\Unit\Actions;

use App\Actions\QrCodeAction;
use Endroid\QrCode\Writer\Result\ResultInterface;
use Tests\TestCase;

class QrCodeTest extends TestCase
{
    /**
     * @test
     * @group u-actions
     */
    public function QrCodeAction()
    {
        $QrCode = (new QrCodeAction)->process('foo');

        $this->assertInstanceOf(ResultInterface::class, $QrCode);
        $this->assertIsString($QrCode->getDataUri());
    }

    /**
     * @test
     * @group u-actions
     */
    public function sizeMin()
    {
        $size = QrCodeAction::MIN_SIZE - 1;
        config(['urlhub.qrcode_size' => $size]);

        $image = imagecreatefromstring((new QrCodeAction)->process('foo')->getString());

        $this->assertNotSame($size, (int) imagesx($image));
        $this->assertSame(QrCodeAction::MIN_SIZE, imagesx($image));
    }

    /**
     * @test
     * @group u-actions
     */
    public function sizeMax()
    {
        $size = QrCodeAction::MAX_SIZE + 1;
        config(['urlhub.qrcode_size' => $size]);

        $image = imagecreatefromstring((new QrCodeAction)->process('foo')->getString());

        $this->assertNotSame($size, imagesx($image));
        $this->assertSame(QrCodeAction::MAX_SIZE, imagesx($image));
    }
}
