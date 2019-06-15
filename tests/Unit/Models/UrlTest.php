<?php

namespace Tests\Unit\Models;

use App\Url;
use Tests\TestCase;

class UrlTest extends TestCase
{
    public function setUp():void
    {
        parent::setUp();

        factory(Url::class)->create([
            'user_id'  => $this->admin()->id,
            'long_url' => 'https://github.com/realodix/urlhub',
            'clicks'   => 10,
            'ip'       => '0.0.0.0',
        ]);

        factory(Url::class)->create([
            'user_id'  => 0,
            'long_url' => 'https://laravel.com/',
            'clicks'   => 10,
            'ip'       => '0.0.0.0',
        ]);

        factory(Url::class)->create([
            'user_id'  => 0,
            'long_url' => 'https://laravel-news.com/',
            'clicks'   => 10,
            'ip'       => '1.1.1.1',
        ]);
    }

    /** @test */
    public function belongs_to_user()
    {
        $url = factory(Url::class)->create([
            'user_id' => $this->admin()->id,
        ]);

        $this->assertTrue($url->user()->exists());
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
    public function getShortUrlAttribute()
    {
        $url = new Url;
        $url->url_key = 'realodix';
        $url->name_order = 'short_url';

        $this->assertSame(
            $url->short_url,
            url('/'.$url->url_key)
        );
    }

    /** @test */
    public function setLongUrlAttribute()
    {
        $url = factory(Url::class)->create([
            'user_id'  => null,
            'long_url' => 'http://example.com/',
        ]);

        $this->assertSame(
            $url->long_url,
            'http://example.com'
        );
    }

    /** @test */
    public function total_short_url()
    {
        $url = new Url;

        $this->assertSame(3, $url->totalShortUrl());
    }

    /** @test */
    public function total_short_url_by_me()
    {
        $url = new Url;

        $this->assertSame(1, $url->totalShortUrlById($this->admin()->id));
    }

    /** @test */
    public function total_short_url_by_guest()
    {
        $url = new Url;

        $this->assertSame(2, $url->totalShortUrlById());
    }

    /** @test */
    public function total_clicks()
    {
        $url = new Url;

        $this->assertSame(30, $url->totalClicks());
    }

    /** @test */
    public function total_clicks_by_me()
    {
        $url = new Url;

        $this->assertSame(10, $url->totalClicksById($this->admin()->id));
    }

    /** @test */
    public function total_clicks_by_guest()
    {
        $url = new Url;

        $this->assertSame(20, $url->totalClicksById());
    }
}
