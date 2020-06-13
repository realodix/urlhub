<?php

namespace Tests\Unit\Helpers;

use Tests\TestCase;

class UrlHelperTest extends TestCase
{
    /**
     * @test
     * @group u-helper
     */
    public function urlLimit()
    {
        $this->assertSame(
            'https://laravel.com/',
            urlLimit('https://laravel.com/')
        );

        $this->assertSame(
            'https://laravel.com/docs/5.7/h...ilable-assertions',
            urlLimit('https://laravel.com/docs/5.7/http-tests#available-assertions')
        );

        $this->assertEquals(
            20,
            strlen(urlLimit('https://laravel.com/docs/5.7/http-tests#available-assertions', 20))
        );
    }

    /**
     * @test
     * @group u-helper
     * @dataProvider removeSchemes
     */
    public function remove_schemes($expected, $actual)
    {
        $this->assertSame($expected, remove_schemes($actual));
    }

    public function removeSchemes()
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
