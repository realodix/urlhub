<?php

namespace Tests\Unit\Helpers;

use Illuminate\Support\Str;
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
            'https://example.com/',
            urlDisplay('https://example.com/')
        );

        $this->assertSame(
            'example.com/',
            urlDisplay('https://example.com/', false)
        );

        $this->assertEquals(
            20,
            strlen(urlDisplay('https://example.com/abcde', true, 20))
        );

        $this->assertEquals(
            'https://example.com/abcde',
            urlDisplay('https://example.com/abcde', true, 0)
        );

        // By Host Length
        $this->assertEquals(
            true,
            Str::endsWith(urlDisplay('https://example.com/abcdefghij', true, 29), '...')
        );

        $this->assertSame(
            true,
            Str::endsWith(urlDisplay('https://example-12345-test.com/abcdefghijklmnopqrstuvwxyz', true, 40), '...')
        );

        $this->assertSame(
            'https://example.com/a...fghijklmnop',
            urlDisplay('https://example.com/abcdefghijklmnop', true, 35)
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
            ['laravel.com', 'laravel.com'],
            ['laravel.com', 'www.laravel.com'],
            ['laravel.com', 'http://laravel.com'],
            ['laravel.com', 'http://www.laravel.com'],
            ['laravel.com', 'https://laravel.com'],
            ['laravel.com', 'https://www.laravel.com'],
        ];
    }
}
