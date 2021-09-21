<?php

namespace Tests\Unit\Services;

use App\Services\UrlService;
use Tests\TestCase;

class UrlServiceTest extends TestCase
{
    /**
     * @var \App\Services\UrlService
     */
    protected $urlSrvc;

    protected function setUp(): void
    {
        parent::setUp();
        $this->urlSrvc = new UrlService();
    }

    /**
     * @test
     * @group u-service
     */
    public function ipToCountryWithKnownIp()
    {
        $countries = $this->urlSrvc->ipToCountry('8.8.8.8');

        $this->assertEquals('US', $countries['countryCode']);
    }

    /**
     * @test
     * @group u-service
     */
    public function ipToCountryWithUnknownIp()
    {
        $countries = $this->urlSrvc->ipToCountry('127.0.0.1');

        $this->assertEquals('N/A', $countries['countryCode']);
        $this->assertEquals('Unknown', $countries['countryName']);
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

    /**
     * @test
     * @group u-service
     * @dataProvider getDomainProvider
     *
     * @param  mixed  $expected
     * @param  mixed  $actutal
     */
    public function getDomain($expected, $actutal)
    {
        $this->assertEquals($expected, $this->urlSrvc->getDomain($actutal));
    }

    public function getDomainProvider()
    {
        return [
            ['foo.com', 'http://foo.com/foo/bar?name=taylor'],
            ['foo.com', 'https://foo.com/foo/bar?name=taylor'],
            ['foo.com', 'http://www.foo.com/foo/bar?name=taylor'],
            ['foo.com', 'https://www.foo.com/foo/bar?name=taylor'],
            ['bar.foo.com', 'http://bar.foo.com/foo/bar?name=taylor'],
            ['bar.foo.com', 'https://bar.foo.com/foo/bar?name=taylor'],
            ['bar.foo.com', 'http://www.bar.foo.com/foo/bar?name=taylor'],
            ['bar.foo.com', 'https://www.bar.foo.com/foo/bar?name=taylor'],
        ];
    }

    /**
     * @test
     * @group u-service
     */
    public function webTitle()
    {
        $longUrl = 'https://github123456789.com';

        $this->assertSame('github123456789.com - No Title', $this->urlSrvc->webTitle($longUrl));
    }
}
