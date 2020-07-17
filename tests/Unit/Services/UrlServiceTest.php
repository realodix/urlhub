<?php

namespace Tests\Unit\Services;

use App\Services\UrlService;
use Tests\TestCase;

class UrlServiceTest extends TestCase
{
    protected $urlSrvc;

    public function setUp(): void
    {
        parent::setUp();

        $this->urlSrvc = new UrlService();
    }

    /**
     * @group u-services
     */
    public function testAnonymizeIpWhenConfigSettedFalse()
    {
        config()->set('urlhub.anonymize_ip_addr', false);

        $ip = '192.168.1.1';
        $expected = $this->urlSrvc->anonymizeIp($ip);
        $actual = $ip;

        $this->assertSame($expected, $actual);
    }
}
