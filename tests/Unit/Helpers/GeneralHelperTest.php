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
    public function strLimit()
    {
        $this->assertSame(
            'https://laravel.com/',
            strLimit('https://laravel.com/')
        );

        $this->assertSame(
            'https://laravel.com/docs/5.7/h...ilable-assertions',
            strLimit('https://laravel.com/docs/5.7/http-tests#available-assertions')
        );

        $this->assertEquals(
            20,
            strlen(strLimit('https://laravel.com/docs/5.7/http-tests#available-assertions', 20))
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
