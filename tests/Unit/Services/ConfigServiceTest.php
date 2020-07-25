<?php

namespace Tests\Unit\Services;

use App\Services\ConfigService;
use Tests\TestCase;

class ConfigServiceTest extends TestCase
{
    /**
     * @test
     * @group u-service
     * @dataProvider isBoolProvider
     */
    public function public_site($value, $expected)
    {
        config(['urlhub.public_site' => $value]);

        (new ConfigService)->configGuard();

        $this->assertSame($expected, uHub('public_site'));
    }

    /**
     * @test
     * @group u-service
     * @dataProvider isBoolProvider
     */
    public function registration($value, $expected)
    {
        config(['urlhub.registration' => $value]);

        (new ConfigService)->configGuard();

        $this->assertSame($expected, uHub('registration'));
    }

    /**
     * @test
     * @group u-service
     * @dataProvider isBoolProvider
     */
    public function guest_show_stat($value, $expected)
    {
        config(['urlhub.guest_show_stat' => $value]);

        (new ConfigService)->configGuard();

        $this->assertSame($expected, uHub('guest_show_stat'));
    }

    /**
     * @test
     * @group u-service
     * @dataProvider hashCharProvider
     */
    public function hash_char($value, $expected)
    {
        config(['urlhub.hash_char' => $value]);

        (new ConfigService)->configGuard();

        $this->assertSame($expected, uHub('hash_char'));
    }

    public function hashCharProvider()
    {
        $defaultValue = ConfigService::DEFAULT_HASH_CHAR;

        return [
            ['string', 'string'],
            ['string=+', $defaultValue], // non-alphanumeric
            [1, $defaultValue],
            [1.1, $defaultValue],
            [true, $defaultValue],
            [null, $defaultValue],
            ['', $defaultValue],
        ];
    }

    /**
     * @test
     * @group u-service
     * @dataProvider hashLengthProvider
     */
    public function hash_length($value, $expected)
    {
        config(['urlhub.hash_length' => $value]);

        (new ConfigService)->configGuard();

        $this->assertSame($expected, uHub('hash_length'));
    }

    public function hashLengthProvider()
    {
        $defaultValue = ConfigService::DEFAULT_HASH_LENGTH;

        return [
            [1, 1],
            ['string', $defaultValue],
            [1.1, $defaultValue],
            [true, $defaultValue],
            [null, $defaultValue],
            ['', $defaultValue],
        ];
    }

    /**
     * @test
     * @group u-service
     * @dataProvider redirectStatusCodeProvider
     */
    public function redirect_status_code($value, $expected)
    {
        config(['urlhub.redirect_status_code' => $value]);

        (new ConfigService)->configGuard();

        $this->assertSame($expected, uHub('redirect_status_code'));
    }

    public function redirectStatusCodeProvider()
    {
        $defaultValue = ConfigService::DEFAULT_REDIRECT_STATUS_CODE;

        return [
            [301, 301],
            [299, $defaultValue],
            [400, $defaultValue],
            ['string', $defaultValue],
            [1.1, $defaultValue],
            [true, $defaultValue],
            [null, $defaultValue],
            ['', $defaultValue],
        ];
    }

    /**
     * @test
     * @group u-service
     * @dataProvider redirectCacheLifetimeProvider
     */
    public function redirect_cache_lifetime($value, $expected)
    {
        config(['urlhub.redirect_cache_lifetime' => $value]);

        (new ConfigService)->configGuard();

        $this->assertSame($expected, uHub('redirect_cache_lifetime'));
    }

    public function redirectCacheLifetimeProvider()
    {
        $defaultValue = ConfigService::DEFAULT_REDIRECT_CACHE_LIFETIME;

        return [
            [1, 1],
            [0, 0],
            [-1, $defaultValue],
            ['string', $defaultValue],
            [1.1, $defaultValue],
            [true, $defaultValue],
            [null, $defaultValue],
            ['', $defaultValue],
        ];
    }

    /**
     * @test
     * @group u-service
     * @dataProvider isBoolProvider
     */
    public function anonymize_ip_addr($value, $expected)
    {
        config(['urlhub.anonymize_ip_addr' => $value]);

        (new ConfigService)->configGuard();

        $this->assertSame($expected, uHub('anonymize_ip_addr'));
    }

    public function isBoolProvider()
    {
        $defaultValue = ConfigService::DEFAULT_TRUE;

        return [
            [true, true],
            ['string', $defaultValue],
            [1, $defaultValue],
            [null, $defaultValue],
            ['', $defaultValue],
        ];
    }
}
