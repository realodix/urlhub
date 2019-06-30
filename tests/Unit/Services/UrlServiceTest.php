<?php

namespace Tests\Unit\Services;

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

    /** @test */
    public function key_generator_length()
    {
        $this->assertSame(config('urlhub.hash_size_1'), strlen($this->UrlSrvc->key_generator()));
    }

    /** @test */
    public function key_generator_size1_equal_with_size2()
    {
        config()->set('urlhub.hash_alphabet', 'abc');
        config()->set('urlhub.hash_size_1', 2);
        config()->set('urlhub.hash_size_2', 2);

        $this->assertSame(config('urlhub.hash_size_1'), strlen($this->UrlSrvc->key_generator()));
    }

    /** @test */
    public function key_generator_size2_with_zero_value()
    {
        config()->set('urlhub.hash_alphabet', 'abc');
        config()->set('urlhub.hash_size_1', 2);
        config()->set('urlhub.hash_size_2', 0);

        $this->assertSame(config('urlhub.hash_size_1'), strlen($this->UrlSrvc->key_generator()));
    }
}
