<?php

namespace Tests\Feature;

use App\Url;
use Tests\TestCase;

class UrlTest extends TestCase
{
    /**
     * @test
     */
    public function create_short_url()
    {
        $long_url = 'https://laravel.com';

        $response = $this->post(route('createshortlink'), [
            'long_url' => $long_url,
        ]);

        $this->assertDatabaseHas('urls', [
            'long_url' => $long_url,
        ]);
    }

    /**
     * @test
     */
    public function create_short_url_with_wrong_url_format()
    {
        $long_url = 'wrong-url-format';

        $response = $this->post(route('createshortlink'), [
            'long_url' => $long_url,
        ]);

        $response->assertRedirect(route('home'));
        $response->assertSessionHasErrors('long_url');
        $response->assertStatus(302);
    }

    /**
     * @test
     */
    public function redirect_to_original_url()
    {
        $long_url = 'https://laravel.com';

        $this->post(route('createshortlink'), [
            'long_url' => $long_url,
        ]);

        $url = Url::whereLongUrl($long_url)
                    ->first();

        $response = $this->get(route('home').'/'.$url->url_key);
        $response->assertRedirect($long_url);
    }

    /*
     |
     | Custom Short URLs
     |
     |
     */

    /**
     * @test
     */
    public function cst_create_short_url()
    {
        $long_url = 'https://laravel.com';
        $custom_url_key = 'laravel';

        $response = $this->post(route('createshortlink'), [
            'long_url'       => $long_url,
            'custom_url_key' => $custom_url_key,
        ]);

        $this->assertDatabaseHas('urls', [
            'long_url' => $long_url,
            'url_key'  => $custom_url_key,
        ]);

        $response = $this->get(route('home').'/'.$custom_url_key);
        $response->assertRedirect($long_url);
    }

    /**
     * @test
     */
    public function cst_create_short_url_2()
    {
        $long_url = 'https://laravel.com';
        $custom_url_key = 'laravel';

        $this->post(route('createshortlink'), [
            'long_url'       => $long_url,
            'custom_url_key' => $custom_url_key,
        ]);

        $long_url_2 = 'https://laravel.com';
        $custom_url_key_2 = 'laravel2';

        $response = $this->post(route('createshortlink'), [
            'long_url'       => $long_url_2,
            'custom_url_key' => $custom_url_key_2,
        ]);

        $response = $this->get(route('home').'/'.$custom_url_key_2);
        $response->assertStatus(404);
    }

    /**
     * @test
     */
    public function cst_long_url_already_exist()
    {
        $url = factory(Url::class)->make();
        $custom_url_key = 'hello';

        $response = $this->post(route('createshortlink'), [
            'long_url' => $url->long_url,
            'custom_url_key'  => $custom_url_key,
        ]);

        $response->assertRedirect(route('short_url.stats', $custom_url_key));
    }

    /**
     * @test
     */
    public function cst_redirect_to_original_url()
    {
        $long_url = 'https://laravel.com';
        $custom_url_key = 'laravel';

        $this->post(route('createshortlink'), [
            'long_url' => $long_url,
            'url_key'  => $custom_url_key,
        ]);

        $url = Url::whereLongUrl($long_url)
                    ->first();

        $response = $this->get(route('home').'/'.$url->url_key);
        $response->assertRedirect($long_url);
    }
}
