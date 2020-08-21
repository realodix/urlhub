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
     */
    public function urlDisplay()
    {
        $this->assertSame(
            'https://example.com/abcde/',
            urlDisplay('https://example.com/abcde/')
        );

        $this->assertSame(
            'https://example.com',
            urlDisplay('https://example.com/')
        );

        // Truncates the given string at the specified length
        $this->assertEquals(
            21,
            strlen(urlDisplay('https://example.com/abcde', true, 21))
        );

        $this->assertSame(
            'https://example.com/abcde...',
            urlDisplay('https://example.com/abcdefghij', true, 28)
        );

        $this->assertSame(
            'https://example.com/abc...hij',
            urlDisplay('https://example.com/abcdefghij', true, 29)
        );

        $this->assertSame(
            'https://example...',
            urlDisplay('https://example.com/abc', true, 18)
        );

        // Remove scheme & truncates
        $this->assertEquals(
            17,
            strlen(urlDisplay('https://example.com/abcde', false, 21))
        );

        $this->assertSame(
            'example.com/abcde...',
            urlDisplay('https://example.com/abcdefghij', false, 20)
        );

        $this->assertSame(
            'example.com/abc...hij',
            urlDisplay('https://example.com/abcdefghij', false, 21)
        );

        $this->assertSame(
            'example...',
            urlDisplay('https://example.com/abc', false, 10)
        );
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
