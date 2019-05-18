<?php

namespace Tests\Unit;

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

    public function test_remove_schemes()
    {
        $this->assertSame('laravel.com', remove_schemes('laravel.com'));
        $this->assertSame('laravel.com', remove_schemes('www.laravel.com'));
        $this->assertSame('laravel.com', remove_schemes('http://laravel.com'));
        $this->assertSame('laravel.com', remove_schemes('http://www.laravel.com'));
        $this->assertSame('laravel.com', remove_schemes('https://laravel.com'));
        $this->assertSame('laravel.com', remove_schemes('https://www.laravel.com'));
    }
}
