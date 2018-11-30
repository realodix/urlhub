<?php

namespace Tests\Unit;

use Facades\App\Helpers\NumHlp;
use Facades\App\Helpers\UrlHlp;
use Tests\TestCase;

class HelpersTest extends TestCase
{
    /*
     * App\Helpers\NumHlp
     */
    public function test_readable_int()
    {
        $this->assertEquals(0, readable_int('string'));
        $this->assertEquals('10', readable_int(10));
        $this->assertEquals('60K+', readable_int(60000));
    }

    public function test_number_format_precision()
    {
        $this->assertEquals(10, NumHlp::number_format_precision(10));
        $this->assertEquals(10.1, NumHlp::number_format_precision(10.1));
        $this->assertEquals(10.11, NumHlp::number_format_precision(10.111));
        $this->assertEquals(10.12, NumHlp::number_format_precision(10.1279));
    }

    /*
     * App\Helpers\UrlHlp
     */
    public function test_url_limit()
    {
        $this->assertSame(
             'https://laravel.com/',
             url_limit('https://laravel.com/')
         );

        $this->assertSame(
             'https://laravel.com/docs/5.7/h...available-assertions',
             url_limit('https://laravel.com/docs/5.7/http-tests#available-assertions')
         );

        $this->assertEquals(
             20 + 3,
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

    public function test_url_key_capacity()
    {
        config()->set('plur.hash_alphabet', 'abc');
        config()->set('plur.hash_size_1', -1);
        config()->set('plur.hash_size_2', -2);
        $this->assertEquals(0, UrlHlp::url_key_capacity());

        config()->set('plur.hash_alphabet', 'abc');
        config()->set('plur.hash_size_1', 0);
        config()->set('plur.hash_size_2', 0);
        $this->assertEquals(0, UrlHlp::url_key_capacity());

        config()->set('plur.hash_alphabet', 'abc');
        config()->set('plur.hash_size_1', 1);
        config()->set('plur.hash_size_2', 2);
        $this->assertEquals(12, UrlHlp::url_key_capacity()); // (3^1)+(3^2)

        config()->set('plur.hash_alphabet', 'abc');
        config()->set('plur.hash_size_1', 2);
        config()->set('plur.hash_size_2', 2);
        // $alphabet_length^$hash_size_1 or 3^2
        $this->assertEquals(9, UrlHlp::url_key_capacity());

        config()->set('plur.hash_alphabet', 'abc');
        config()->set('plur.hash_size_1', 2.7);
        config()->set('plur.hash_size_2', 2);
        // $alphabet_length^$hash_size_1 or 3^2
        $this->assertEquals(9, UrlHlp::url_key_capacity());

        config()->set('plur.hash_alphabet', 'abc');
        config()->set('plur.hash_size_1', 2);
        config()->set('plur.hash_size_2', 2.7);
        // $alphabet_length^$hash_size_1 or 3^2
        $this->assertEquals(9, UrlHlp::url_key_capacity());

        config()->set('plur.hash_alphabet', 'abc');
        config()->set('plur.hash_size_1', 'string');
        config()->set('plur.hash_size_2', 2);
        $this->assertEquals(10, UrlHlp::url_key_capacity()); // (3^0)+(3^2)

        config()->set('plur.hash_alphabet', 'abc');
        config()->set('plur.hash_size_1', 2);
        config()->set('plur.hash_size_2', 'string');
        // $alphabet_length^$hash_size_1 or 3^2
        $this->assertEquals(9, UrlHlp::url_key_capacity());

        config()->set('plur.hash_alphabet', 'abc');
        config()->set('plur.hash_size_1', 'string');
        config()->set('plur.hash_size_2', 'string');
        $this->assertEquals(0, UrlHlp::url_key_capacity());
    }
}
