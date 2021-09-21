<?php

namespace Tests\Unit\Services;

use App\Services\ConfigService;
use Tests\TestCase;

class ConfigServiceTest extends TestCase
{
    /**
     * @test
     * @group u-service
     * @dataProvider hashCharProvider
     *
     * @param  mixed  $value
     * @param  mixed  $expected
     */
    public function hashChar($value, $expected)
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
            ['', $defaultValue],
        ];
    }

    /**
     * @test
     * @group u-service
     * @dataProvider hashLengthProvider
     *
     * @param  mixed  $value
     * @param  mixed  $expected
     */
    public function hashLength($value, $expected)
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
            [1.1, $defaultValue],
            ['', $defaultValue],
        ];
    }

    /**
     * @test
     * @group u-service
     * @dataProvider redirectStatusCodeProvider
     *
     * @param  mixed  $value
     * @param  mixed  $expected
     */
    public function redirectStatusCode($value, $expected)
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
            ['', $defaultValue],
        ];
    }

    /**
     * @test
     * @group u-service
     * @dataProvider redirectCacheLifetimeProvider
     *
     * @param  mixed  $value
     * @param  mixed  $expected
     */
    public function redirectCacheLifetime($value, $expected)
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
            [1.1, $defaultValue],
            ['', $defaultValue],
        ];
    }
}
