<?php

namespace Tests\Unit\Helpers;

use Tests\TestCase;

class UrlHlpTest extends TestCase
{
    public function test_url_limit()
    {
        $this->assertSame(
             'https://laravel.com/',
             url_limit('https://laravel.com/')
        );

        $this->assertSame(
             'https://laravel.com/docs/5.7/h...ilable-assertions',
             url_limit('https://laravel.com/docs/5.7/http-tests#available-assertions')
        );

        $this->assertEquals(
             20,
             strlen(url_limit('https://laravel.com/docs/5.7/http-tests#available-assertions', 20))
        );
    }

    /**
     * @dataProvider removeSchemes
     */
    public function test_remove_schemes($expected, $actual)
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
