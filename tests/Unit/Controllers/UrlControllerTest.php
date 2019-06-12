<?php

namespace Tests\Unit\Controllers;

use App\Url;
use Tests\TestCase;

/**
 * App\Http\Controllers\UrlController;.
 */
class UrlControllerTest extends TestCase
{
    /**
     * create().
     * @test
     */
    public function create()
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
     * With custom URL.
     *
     * create()
     *
     * @test
     */
    public function create_2()
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
    }

    /**
     * urlRedirection().
     * @test
     */
    public function url_redirection()
    {
        $long_url = 'https://laravel.com';

        $this->post(route('createshortlink'), [
            'long_url' => $long_url,
        ]);

        $url = Url::whereLongUrl($long_url)
                    ->first();

        $response = $this->get(route('home').'/'.$url->url_key);
        $response->assertRedirect($long_url);
        $response->assertStatus(301);
    }

    /**
     * urlRedirection().
     * @test
     */

    /** @test */
    public function url_redirection_2()
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
        $response->assertStatus(301);
    }

    /*
     * checkExistingCustomUrl()
     * @test
     */
    // public function bb()
    // {

    // }
}
