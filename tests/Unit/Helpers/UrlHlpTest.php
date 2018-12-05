<?php

namespace Tests\Unit;

use Facades\App\Helpers\UrlHlp;
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

    public function test_url_key_capacity()
    {
        config()->set('plur.hash_alphabet', 'abc');
        config()->set('plur.hash_size_1', 0);
        config()->set('plur.hash_size_2', 0);
        $this->assertSame(0, UrlHlp::url_key_capacity());

        config()->set('plur.hash_alphabet', 'abc');
        config()->set('plur.hash_size_1', 1);
        config()->set('plur.hash_size_2', 2);
        $this->assertSame(12, UrlHlp::url_key_capacity()); // (3^1)+(3^2)

        config()->set('plur.hash_alphabet', 'abc');
        config()->set('plur.hash_size_1', 2);
        config()->set('plur.hash_size_2', 2);
        // $alphabet_length^$hash_size_1 or 3^2
        $this->assertSame(9, UrlHlp::url_key_capacity());
    }

    public function test_url_key_capacity_input_negative()
    {
        config()->set('plur.hash_alphabet', 'abc');
        config()->set('plur.hash_size_1', 1);
        config()->set('plur.hash_size_2', -2);
        $this->assertSame(3, UrlHlp::url_key_capacity());

        config()->set('plur.hash_alphabet', 'abc');
        config()->set('plur.hash_size_1', -1);
        config()->set('plur.hash_size_2', 2);
        $this->assertSame(0, UrlHlp::url_key_capacity());

        config()->set('plur.hash_alphabet', 'abc');
        config()->set('plur.hash_size_1', -1);
        config()->set('plur.hash_size_2', -2);
        $this->assertSame(0, UrlHlp::url_key_capacity());
    }

    public function test_url_key_capacity_input_number()
    {
        config()->set('plur.hash_alphabet', 'abc');
        config()->set('plur.hash_size_1', 2.7);
        config()->set('plur.hash_size_2', 3);
        $this->assertSame(36, UrlHlp::url_key_capacity()); // (3^2)+(3^3)

        config()->set('plur.hash_alphabet', 'abc');
        config()->set('plur.hash_size_1', 2);
        config()->set('plur.hash_size_2', 3.7);
        $this->assertSame(36, UrlHlp::url_key_capacity()); // (3^2)+(3^3)
    }

    public function test_url_key_capacity_input_string()
    {
        config()->set('plur.hash_alphabet', 'abc');
        config()->set('plur.hash_size_1', 'string');
        config()->set('plur.hash_size_2', 2);
        $this->assertSame(0, UrlHlp::url_key_capacity());

        config()->set('plur.hash_alphabet', 'abc');
        config()->set('plur.hash_size_1', 2);
        config()->set('plur.hash_size_2', 'string');
        // $alphabet_length^$hash_size_1 or 3^2
        $this->assertSame(9, UrlHlp::url_key_capacity());

        config()->set('plur.hash_alphabet', 'abc');
        config()->set('plur.hash_size_1', 'string');
        config()->set('plur.hash_size_2', 'string');
        $this->assertSame(0, UrlHlp::url_key_capacity());
    }
}
