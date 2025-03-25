<?php

namespace Tests\Unit\Services;

use DeviceDetector\Parser\OperatingSystem as OS;
use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

#[PHPUnit\Group('services')]
class DeviceDetectorServiceTest extends TestCase
{
    public function testDeviceDetectorService()
    {
        $userAgent = 'Mozilla/5.0 (iPad; CPU OS 18_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/128.0.6613.34 Mobile/15E148 Safari/604.1';
        $device = app(\App\Services\DeviceDetectorService::class);
        $device->setUserAgent($userAgent);
        $device->parse();

        $this->assertEquals('Chrome Mobile iOS', $device->getClientAttr('name'));
        $this->assertEquals('iPadOS', $device->getOsAttr('name'));
        $this->assertEquals('iOS', $device->getOsAttr('family'));
    }

    public function testOsNameIsCorrect(): void
    {
        $this->assertSame('Android', OS::getNameFromId('AND'));
        $this->assertSame('iOS', OS::getNameFromId('IOS'));
    }
}
