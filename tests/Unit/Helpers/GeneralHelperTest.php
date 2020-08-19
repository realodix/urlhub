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

        // Remove URL scheme
        $this->assertSame(
            'example.com/abcde',
            urlDisplay('https://example.com/abcde', false)
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
            'https://example.com/a...fghijklmnop',
            urlDisplay('https://example.com/abcdefghijklmnop', true, 35)
        );

        $this->assertSame(
            't.co/a...',
            urlDisplay('https://t.co/abcde', false, 9)
        );

        $this->assertSame(
            't.co/abc...hij',
            urlDisplay('https://t.co/abcdefghij', false, 14)
        );
    }

    /**
     * @test
     * @group u-helper
     * @dataProvider urlRemoveSchemeProvider
     */
    public function urlRemoveScheme($expected, $actual)
    {
        $this->assertSame($expected, urlRemoveScheme($actual));
    }

    public function urlRemoveSchemeProvider()
    {
        return [
            ['laravel.com', 'laravel.com/'],
            ['laravel.com/a/b', 'www.laravel.com/a/b/'],
            ['laravel.com', 'http://laravel.com'],
            ['laravel.com', 'http://www.laravel.com'],
            ['laravel.com', 'https://laravel.com'],
            ['laravel.com', 'https://www.laravel.com'],
        ];
    }
}
