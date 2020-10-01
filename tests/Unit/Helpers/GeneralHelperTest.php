<?php

namespace Tests\Unit\Helpers;

use Tests\TestCase;

class GeneralHelperTest extends TestCase
{
    /**
     * @group u-helper
     */
    public function test_uHub()
    {
        $expected = config('urlhub.hash_length');
        $actual = uHub('hash_length');
        $this->assertSame($expected, $actual);
    }

    /**
     * @group u-helper
     */
    public function testAppName()
    {
        $expected = config('app.name');
        $actual = appName();
        $this->assertSame($expected, $actual);
    }

    /**
     * @test
     * @group u-helper
     * @dataProvider urlSanitizeProvider
     */
    public function urlSanitize($expected, $actual)
    {
        $this->assertSame($expected, urlSanitize($actual));
    }

    public function urlSanitizeProvider()
    {
        return [
            ['laravel.com', 'laravel.com'],
            ['laravel.com', 'www.laravel.com'],
            ['laravel.com', 'http://laravel.com'],
            ['laravel.com', 'http://www.laravel.com'],
            ['laravel.com', 'https://laravel.com'],
            ['laravel.com', 'https://www.laravel.com'],
            ['laravel.com', 'https://www.laravel.com/'],
            ['laravel.com/abc', 'https://www.laravel.com/abc'],
            ['laravel.com/abc', 'https://www.laravel.com/abc/'],
        ];
    }
}
