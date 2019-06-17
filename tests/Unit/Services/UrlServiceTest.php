<?php

namespace Tests\Unit\Services;

use App\Services\UrlService;
use App\Url;
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

    /** @test */
    public function url_key_capacity()
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

    /** @test */
    public function url_key_capacity_input_negative()
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

    /** @test */
    public function url_key_capacity_input_number()
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

    /** @test */
    public function url_key_capacity_input_string()
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

    /** @test */
    public function url_key_remaining()
    {
        factory(Url::class, 5)->create([
            'user_id' => null,
        ]);

        config()->set('urlhub.hash_alphabet', 'abc');
        config()->set('urlhub.hash_size_1', 1);
        config()->set('urlhub.hash_size_2', 0);

        // 3 - 5 = must be 0
        $this->assertSame(0, $this->UrlSrvc->url_key_remaining());

        config()->set('urlhub.hash_alphabet', 'abc');
        config()->set('urlhub.hash_size_1', 2);
        config()->set('urlhub.hash_size_2', 0);

        // 9 - 5 = 4
        $this->assertSame(4, $this->UrlSrvc->url_key_remaining());
    }

    /**
     * @test
     * @dataProvider getDomainProvider
     */
    public function get_domain($expected, $actutal)
    {
        $this->assertEquals($expected, $this->UrlSrvc->getDomain($actutal));
    }

    public function getDomainProvider()
    {
        return [
            ['foo.com', 'http://foo.com/foo/bar?name=taylor'],
            ['foo.com', 'https://foo.com/foo/bar?name=taylor'],
            ['foo.com', 'http://www.foo.com/foo/bar?name=taylor'],
            ['foo.com', 'https://www.foo.com/foo/bar?name=taylor'],
            ['foo.com', 'http://bar.foo.com/foo/bar?name=taylor'],
            ['foo.com', 'https://bar.foo.com/foo/bar?name=taylor'],
            ['foo.com', 'http://www.bar.foo.com/foo/bar?name=taylor'],
            ['foo.com', 'https://www.bar.foo.com/foo/bar?name=taylor'],
        ];
    }
}
