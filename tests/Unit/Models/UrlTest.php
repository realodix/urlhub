<?php

namespace Tests\Unit\Models;

use App\Url;
use App\UrlStat;
use Tests\TestCase;

class UrlTest extends TestCase
{
    protected $url;

    public function setUp():void
    {
        parent::setUp();

        $this->url = new Url();

        factory(Url::class)->create([
            'user_id' => $this->admin()->id,
            'clicks'  => 10,
        ]);

        factory(Url::class, 2)->create([
            'user_id' => null,
            'clicks'  => 10,
        ]);

        config()->set('urlhub.hash_alphabet', 'abc');
    }

    /** @test */
    public function belongs_to_user()
    {
        $url = factory(Url::class)->create([
            'user_id' => $this->admin()->id,
        ]);

        $this->assertTrue($url->user()->exists());
    }

    /** @test */
    public function has_many_url_stat()
    {
        $url = factory(Url::class)->create();

        $url_stat = factory(UrlStat::class)->create([
            'url_id' => $url->id,
        ]);

        $this->assertTrue($url->urlStat()->exists());
    }

    /**
     * The default guest id must be null.
     *
     * @test
     */
    public function default_guest_id()
    {
        $long_url = 'https://example.com';

        $this->post(route('createshortlink'), [
            'long_url' => $long_url,
        ]);

        $url = Url::whereLongUrl($long_url)->first();

        $this->assertSame(null, $url->user_id);
    }

    /** @test */
    public function default_guest_name()
    {
        $url = factory(Url::class)->create([
            'user_id' => null,
        ]);

        $this->assertSame('Guest', $url->user->name);
    }

    /** @test */
    public function setUserIdAttribute_must_be_null()
    {
        $url = factory(Url::class)->create([
            'user_id' => 0,
        ]);

        $this->assertEquals(null, $url->user_id);
    }

    /** @test */
    public function setLongUrlAttribute()
    {
        $url = factory(Url::class)->create([
            'long_url' => 'http://example.com/',
        ]);

        $this->assertSame(
            $url->long_url,
            'http://example.com'
        );
    }

    /** @test */
    public function getShortUrlAttribute()
    {
        $url = Url::whereUserId($this->admin()->id)->first();

        $this->assertSame(
            $url->short_url,
            url('/'.$url->url_key)
        );
    }

    /** @test */
    public function total_short_url()
    {
        $this->assertSame(
            3,
            $this->url->totalShortUrl()
        );
    }

    /** @test */
    public function total_short_url_by_me()
    {
        $this->assertSame(
            1,
            $this->url->totalShortUrlById($this->admin()->id)
        );
    }

    /** @test */
    public function total_short_url_by_guest()
    {
        $this->assertSame(
            2,
            $this->url->totalShortUrlById()
        );
    }

    /** @test */
    public function total_clicks()
    {
        $this->assertSame(
            30,
            $this->url->totalClicks()
        );
    }

    /** @test */
    public function total_clicks_by_me()
    {
        $this->assertSame(
            10,
            $this->url->totalClicksById($this->admin()->id)
        );
    }

    /**
     * The number of guests is calculated based on a unique IP.
     *
     * @test
     */
    public function total_clicks_by_guest()
    {
        $this->assertSame(
            20,
            $this->url->totalClicksById()
        );
    }

    /** @test */
    public function key_generator_length()
    {
        $this->assertSame(
            config('urlhub.hash_size_1'),
            strlen($this->url->key_generator())
        );
    }

    /** @test */
    public function key_generator_size1_equal_with_size2()
    {
        config()->set('urlhub.hash_size_1', 2);
        config()->set('urlhub.hash_size_2', 2);

        $this->assertSame(
            config('urlhub.hash_size_1'),
            strlen($this->url->key_generator())
        );
    }

    /** @test */
    public function key_generator_size2_with_zero_value()
    {
        config()->set('urlhub.hash_size_1', 2);
        config()->set('urlhub.hash_size_2', 0);

        $this->assertSame(
            config('urlhub.hash_size_1'),
            strlen($this->url->key_generator())
        );
    }

    /** @test */
    public function url_key_capacity()
    {
        config()->set('urlhub.hash_size_1', 0);
        config()->set('urlhub.hash_size_2', 0);
        $this->assertSame(0, $this->url->url_key_capacity());

        config()->set('urlhub.hash_size_1', 1);
        config()->set('urlhub.hash_size_2', 2);
        $this->assertSame(12, $this->url->url_key_capacity()); // (3^1)+(3^2)

        config()->set('urlhub.hash_size_1', 2);
        config()->set('urlhub.hash_size_2', 2);
        // $alphabet_length^$hash_size_1 or 3^2
        $this->assertSame(9, $this->url->url_key_capacity());
    }

    /** @test */
    public function url_key_capacity_input_negative()
    {
        config()->set('urlhub.hash_size_1', 1);
        config()->set('urlhub.hash_size_2', -2);
        $this->assertSame(3, $this->url->url_key_capacity());

        config()->set('urlhub.hash_size_1', -1);
        config()->set('urlhub.hash_size_2', 2);
        $this->assertSame(0, $this->url->url_key_capacity());

        config()->set('urlhub.hash_size_1', -1);
        config()->set('urlhub.hash_size_2', -2);
        $this->assertSame(0, $this->url->url_key_capacity());
    }

    /** @test */
    public function url_key_capacity_input_number()
    {
        config()->set('urlhub.hash_size_1', 2.7);
        config()->set('urlhub.hash_size_2', 3);
        $this->assertSame(36, $this->url->url_key_capacity()); // (3^2)+(3^3)

        config()->set('urlhub.hash_size_1', 2);
        config()->set('urlhub.hash_size_2', 3.7);
        $this->assertSame(36, $this->url->url_key_capacity()); // (3^2)+(3^3)
    }

    /** @test */
    public function url_key_capacity_input_string()
    {
        config()->set('urlhub.hash_size_1', 'string');
        config()->set('urlhub.hash_size_2', 2);
        $this->assertSame(0, $this->url->url_key_capacity());

        config()->set('urlhub.hash_size_1', 2);
        config()->set('urlhub.hash_size_2', 'string');
        // $alphabet_length^$hash_size_1 or 3^2
        $this->assertSame(9, $this->url->url_key_capacity());

        config()->set('urlhub.hash_size_1', 'string');
        config()->set('urlhub.hash_size_2', 'string');
        $this->assertSame(0, $this->url->url_key_capacity());
    }

    /** @test */
    public function url_key_remaining()
    {
        factory(Url::class, 5)->create();

        config()->set('urlhub.hash_size_1', 1);
        config()->set('urlhub.hash_size_2', 0);

        // 3 - 5 = must be 0
        $this->assertSame(0, $this->url->url_key_remaining());

        config()->set('urlhub.hash_size_1', 2);

        // (3^2) - 5 - (2+1) = 1
        $this->assertSame(1, $this->url->url_key_remaining());
    }

    /**
     * @test
     * @dataProvider getDomainProvider
     */
    public function get_domain($expected, $actutal)
    {
        $this->assertEquals($expected, $this->url->getDomain($actutal));
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
