<?php

namespace Tests\Unit\Models;

use App\Services\UrlService;
use Tests\TestCase;

class UrlServiceTest extends TestCase
{
    protected $UrlSrvc;

    public function setUp():void
    {
        parent::setUp();

        $this->UrlSrvc = new UrlService();
    }

    public function test_key_generator_length()
    {
        $this->assertSame(config('urlhub.hash_size_1'), strlen($this->UrlSrvc->key_generator()));
    }

    public function test_url_key_capacity()
    {
        config()->set('urlhub.hash_alphabet', 'abc');
        config()->set('urlhub.hash_size_1', 0);
        config()->set('urlhub.hash_size_2', 0);
        $this->assertSame(0, $this->UrlSrvc->url_key_capacity());

        config()->set('urlhub.hash_alphabet', 'abc');
        config()->set('urlhub.hash_size_1', 1);
        config()->set('urlhub.hash_size_2', 2);
        $this->assertSame(12, $this->UrlSrvc->url_key_capacity()); // (3^1)+(3^2)

        config()->set('urlhub.hash_alphabet', 'abc');
        config()->set('urlhub.hash_size_1', 2);
        config()->set('urlhub.hash_size_2', 2);
        // $alphabet_length^$hash_size_1 or 3^2
        $this->assertSame(9, $this->UrlSrvc->url_key_capacity());
    }

    public function test_url_key_capacity_input_negative()
    {
        config()->set('urlhub.hash_alphabet', 'abc');
        config()->set('urlhub.hash_size_1', 1);
        config()->set('urlhub.hash_size_2', -2);
        $this->assertSame(3, $this->UrlSrvc->url_key_capacity());

        config()->set('urlhub.hash_alphabet', 'abc');
        config()->set('urlhub.hash_size_1', -1);
        config()->set('urlhub.hash_size_2', 2);
        $this->assertSame(0, $this->UrlSrvc->url_key_capacity());

        config()->set('urlhub.hash_alphabet', 'abc');
        config()->set('urlhub.hash_size_1', -1);
        config()->set('urlhub.hash_size_2', -2);
        $this->assertSame(0, $this->UrlSrvc->url_key_capacity());
    }

    public function test_url_key_capacity_input_number()
    {
        config()->set('urlhub.hash_alphabet', 'abc');
        config()->set('urlhub.hash_size_1', 2.7);
        config()->set('urlhub.hash_size_2', 3);
        $this->assertSame(36, $this->UrlSrvc->url_key_capacity()); // (3^2)+(3^3)

        config()->set('urlhub.hash_alphabet', 'abc');
        config()->set('urlhub.hash_size_1', 2);
        config()->set('urlhub.hash_size_2', 3.7);
        $this->assertSame(36, $this->UrlSrvc->url_key_capacity()); // (3^2)+(3^3)
    }

    public function test_url_key_capacity_input_string()
    {
        config()->set('urlhub.hash_alphabet', 'abc');
        config()->set('urlhub.hash_size_1', 'string');
        config()->set('urlhub.hash_size_2', 2);
        $this->assertSame(0, $this->UrlSrvc->url_key_capacity());

        config()->set('urlhub.hash_alphabet', 'abc');
        config()->set('urlhub.hash_size_1', 2);
        config()->set('urlhub.hash_size_2', 'string');
        // $alphabet_length^$hash_size_1 or 3^2
        $this->assertSame(9, $this->UrlSrvc->url_key_capacity());

        config()->set('urlhub.hash_alphabet', 'abc');
        config()->set('urlhub.hash_size_1', 'string');
        config()->set('urlhub.hash_size_2', 'string');
        $this->assertSame(0, $this->UrlSrvc->url_key_capacity());
    }
}
