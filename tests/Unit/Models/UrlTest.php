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
            'long_url' => 'https://laravel.com',
            'clicks'   => 10,
            'ip'       => '0.0.0.0',
        ]);

        factory(Url::class)->create([
            'user_id'  => 0,
            'long_url' => 'https://laravel.com',
            'clicks'   => 10,
            'ip'       => '0.0.0.0',
        ]);

        factory(Url::class)->create([
            'user_id'  => 0,
            'long_url' => 'https://laravel.com',
            'clicks'   => 10,
            'ip'       => '1.1.1.1',
        ]);
    }

    /** @test */
    public function belongs_to_user()
    {
        $url = factory(Url::class)->create(['user_id' => $this->user()->id]);

        $this->assertTrue($url->user()->exists());
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
        $url = factory(Url::class)
               ->create(['user_id' => null]);

        $this->assertSame(
            $url->long_url,
            'https://github.com/realodix/urlhub'
        );
    }

    /** @test */
    public function total_short_url()
    {
        $url = new Url;

        $this->assertEquals(3, $url->totalShortUrl());
    }

    /** @test */
    public function total_short_url_by_me()
    {
        $url = new Url;

        $this->assertEquals(1, $url->totalShortUrlById($this->admin()->id));
    }

    /** @test */
    public function total_short_url_by_guest()
    {
        $url = new Url;

        $this->assertEquals(2, $url->totalShortUrlById());
    }

    /** @test */
    public function total_clicks()
    {
        $url = new Url;

        $this->assertEquals(30, $url->totalClicks());
    }

    /** @test */
    public function total_clicks_by_me()
    {
        $url = new Url;

        $this->assertEquals(10, $url->totalClicksById($this->admin()->id));
    }

    /** @test */
    public function total_clicks_by_guest()
    {
        $url = new Url;

        $this->assertEquals(20, $url->totalClicksById());
    }
}
