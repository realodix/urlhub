<?php

namespace Tests\Unit\Models;

use App\Url;
use App\User;
use Tests\TestCase;

class UrlTest extends TestCase
{
    public function test_it_belongs_to_user()
    {
        $user = factory(User::class)->create([]);
        $url = factory(Url::class)->create(['user_id' => $user->id]);

        $this->assertTrue($url->user()->exists());
    }

    public function test_getShortUrlAttribute()
    {
        $url = new Url;
        $url->url_key = 'realodix';
        $url->name_order = 'short_url';

        $this->assertSame(
            $url->short_url,
            url('/'.$url->url_key)
        );
    }

    public function test_setLongUrlAttribute()
    {
        $url = factory(Url::class)
               ->create([
                   'user_id'  => null,
                   'long_url' => 'https://laravel.com/',
               ]);

        $this->assertSame(
            $url->long_url,
            rtrim($url->long_url, '/')
        );
    }
}
