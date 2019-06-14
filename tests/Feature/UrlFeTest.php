<?php

namespace Tests\Feature;

use App\Url;
use Tests\TestCase;

class UrlFeTest extends TestCase
{
    /** @test */
    public function long_url_already_exist()
    {
        $long_url = 'https://laravel.com';

        factory(Url::class)->create([
            'user_id'  => null,
            'long_url' => $long_url,
        ]);

        $url = Url::whereLongUrl($long_url)->first();

        $response = $this->post(route('createshortlink'), [
            'long_url' => $long_url,
        ]);
        $response->assertRedirect(route('short_url.stats', $url->url_key));
    }

    /** @test */
    public function long_url_already_exist_2()
    {
        $long_url = 'https://laravel.com';

        factory(Url::class)->create([
            'user_id'  => null,
            'long_url' => $long_url,
        ]);

        $this->loginAsNonAdmin();

        $response = $this->post(route('createshortlink'), [
            'long_url' => $long_url,
        ]);

        $url = Url::whereUserId($this->nonAdmin()->id)->first();
        $response->assertRedirect(route('short_url.stats', $url->url_key));

        $count = Url::whereLongUrl($long_url)->count();
        $this->assertSame(2, $count);
    }

    /** @test */
    public function long_url_already_exist_3()
    {
        $this->loginAsNonAdmin();
        $user = $this->nonAdmin();

        $long_url = 'https://laravel.com';

        factory(Url::class)->create([
            'user_id'  => $user->id,
            'long_url' => $long_url,
        ]);

        $response = $this->post(route('createshortlink'), [
            'long_url' => $long_url,
        ]);
        $url = Url::whereUserId($user->id)->first();
        $response
            ->assertRedirect(route('short_url.stats', $url->url_key))
            ->assertSessionHas(['msgLinkAlreadyExists']);

        $count = Url::whereLongUrl($long_url)->count();
        $this->assertSame(1, $count);
    }

    /** @test */
    public function duplicate()
    {
        $this->loginAsNonAdmin();

        $long_url = 'https://laravel.com';

        factory(Url::class)->create([
            'user_id'  => $this->nonAdmin()->id,
            'long_url' => $long_url,
        ]);

        $this->post(route('createshortlink'), [
            'long_url' => $long_url,
        ]);

        $url = Url::whereUserId($this->nonAdmin()->id)->first();

        $response = $this->from(route('short_url.stats', $url->url_key))
                         ->get(route('duplicate', $url->url_key));

        $count = Url::whereLongUrl($long_url)->count();
        $this->assertSame(2, $count);
    }

    /** @test */
    public function create_short_url_with_wrong_url_format()
    {
        $long_url = 'wrong-url-format';

        $response = $this->post(route('createshortlink'), [
            'long_url' => $long_url,
        ]);

        $response
            ->assertRedirect(route('home'))
            ->assertSessionHasErrors('long_url');
    }

    /*
     |
     | Custom Short URLs
     |
     |
     */

    /** @test */
    public function cst_long_url_already_exist()
    {
        $long_url = 'https://laravel.com';
        $custom_url_key = 'laravel';

        factory(Url::class)->create([
            'user_id'  => null,
            'long_url' => $long_url,
            'url_key'  => $custom_url_key,
        ]);

        $custom_url_key_2 = 'laravel2';

        $response = $this->post(route('createshortlink'), [
            'long_url'       => $long_url,
            'custom_url_key' => $custom_url_key_2,
        ]);
        $response->assertRedirect(
            route('short_url.stats', $custom_url_key)
        );

        $response2 = $this->get(route('home').'/'.$custom_url_key_2);
        $response2->assertStatus(404);
    }

    /** @test */
    public function cst_long_url_already_exist_2()
    {
        $long_url = 'https://laravel.com';
        $custom_url_key = 'laravel';

        factory(Url::class)->create([
            'user_id'  => null,
            'long_url' => $long_url,
            'url_key'  => $custom_url_key,
        ]);

        $this->loginAsNonAdmin();

        $custom_url_key_2 = 'laravel2';

        $response = $this->post(route('createshortlink'), [
            'long_url'       => $long_url,
            'custom_url_key' => $custom_url_key_2,
        ]);

        $response->assertRedirect(
            route('short_url.stats', $custom_url_key_2)
        );

        $response2 = $this->get(route('home').'/'.$custom_url_key_2);
        $response2->assertRedirect($long_url);

        $count = Url::whereLongUrl($long_url)->count();
        $this->assertSame(2, $count);
    }

    /** @test */
    public function cst_cst_url_key_already_exist()
    {
        $long_url = 'https://laravel.com';
        $custom_url_key = 'laravel';

        factory(Url::class)->create([
            'user_id'  => null,
            'long_url' => $long_url,
            'url_key'  => $custom_url_key,
        ]);

        $response = $this->post(route('createshortlink'), [
            'long_url'       => 'https://laravel-news.com',
            'custom_url_key' => $custom_url_key,
        ]);

        $response
            ->assertRedirect(route('home'))
            ->assertSessionHasErrors('custom_url_key');

        $count = Url::whereLongUrl($long_url)->count();
        $this->assertSame(1, $count);
    }

    /** @test */
    public function cst_cst_url_key_already_exist_2()
    {
        $long_url = 'https://laravel.com';
        $custom_url_key = 'laravel';

        factory(Url::class)->create([
            'user_id'  => null,
            'long_url' => $long_url,
            'url_key'  => $custom_url_key,
        ]);

        $this->loginAsNonAdmin();

        $response = $this->post(route('createshortlink'), [
            'long_url'       => 'https://laravel-news.com',
            'custom_url_key' => $custom_url_key,
        ]);
        $response
            ->assertRedirect(route('home'))
            ->assertSessionHasErrors('custom_url_key');

        $count = Url::whereLongUrl($long_url)->count();
        $this->assertSame(1, $count);
    }
}
