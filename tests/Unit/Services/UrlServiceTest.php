<?php

namespace Tests\Unit\Rule;

use App\Services\UrlService;
use Tests\TestCase;

class UrlServiceTest extends TestCase
{
    protected $urlService;

    public function setUp(): void
    {
        parent::setUp();

        $this->urlService = new UrlService();
    }

    /**
     * @group u-services
     */
    public function testAnonymizeIpWhenConfigSettedFalse()
    {
        config()->set('urlhub.anonymize_ip_addr', false);

        $ip = '192.168.1.1';
        $expected = $this->urlService->anonymizeIp($ip);
        $actual = $ip;

        $this->assertSame($expected, $actual);
    }
}
