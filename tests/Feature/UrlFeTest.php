<?php

namespace Tests\Feature;

use App\Url;
use Tests\TestCase;

/**
 * Front-End Test.
 */
class UrlFeTest extends TestCase
{
    /**
     * @test
     */
    public function create()
    {
        $long_url = 'https://laravel.com';
        $response = $this->post(route('createshortlink'), [
            'long_url' => $long_url,
        ]);

        $url = Url::whereLongUrl($long_url)->first();

        $response->assertRedirect(route('short_url.stats', $url->url_key));
    }

    /**
     * Custom URL.
     *
     * @test
     */
    public function create_cst()
    {
        $long_url = 'https://laravel.com';
        $custom_url_key = 'laravel';

        $response = $this->post(route('createshortlink'), [
            'long_url'       => $long_url,
            'custom_url_key' => $custom_url_key,
        ]);
        $response->assertRedirect(route('short_url.stats', $custom_url_key));
    }

    /**
     * Custom URL, with authenticated user.
     *
     * @test
     */
    public function create_cst_2()
    {
        $this->loginAsAdmin();

        $user = $this->admin();
        $long_url = 'https://laravel.com';
        $custom_url_key = 'laravel';

        $response = $this->post(route('createshortlink'), [
            'long_url'       => $long_url,
            'custom_url_key' => $custom_url_key,
        ]);
        $response->assertRedirect(route('short_url.stats', $custom_url_key));
    }

    /** @test */
    public function long_url_already_exist()
    {
        $url = factory(Url::class)->create([
            'user_id' => null,
        ]);

        $response = $this->post(route('createshortlink'), [
            'long_url' => $url->long_url,
        ]);
        $response->assertRedirect(route('short_url.stats', $url->url_key));
    }

    /** @test */
    public function long_url_already_exist_2()
    {
        $url = factory(Url::class)->create([
            'user_id' => null,
        ]);

        $this->loginAsNonAdmin();

        $response = $this->post(route('createshortlink'), [
            'long_url' => $url->long_url,
        ]);

        $url = Url::whereUserId($this->nonAdmin()->id)->first();
        $response->assertRedirect(route('short_url.stats', $url->url_key));

        $this->assertCount(2, Url::all());
    }

    /** @test */
    public function long_url_already_exist_3()
    {
        $this->loginAsNonAdmin();
        $user = $this->nonAdmin();

        $url = factory(Url::class)->create([
            'user_id' => $user->id,
        ]);

        $response = $this->post(route('createshortlink'), [
            'long_url' => $url->long_url,
        ]);

        $response
            ->assertRedirect(route('short_url.stats', $url->url_key))
            ->assertSessionHas(['msgLinkAlreadyExists']);

        $this->assertCount(1, Url::all());
    }

    /** @test */
    public function duplicate()
    {
        $this->loginAsNonAdmin();

        $url = factory(Url::class)->create([
            'user_id' => $this->nonAdmin()->id,
        ]);

        $this->post(route('createshortlink'), [
            'long_url' => $url->long_url,
        ]);

        $this->from(route('short_url.stats', $url->url_key))
             ->get(route('duplicate', $url->url_key));

        $this->assertCount(2, Url::all());
    }

    /** @test */
    public function create_short_url_with_wrong_url_format()
    {
        $response = $this->post(route('createshortlink'), [
            'long_url' => 'wrong-url-format',
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
        $url = factory(Url::class)->create([
            'user_id' => null,
        ]);

        $custom_url_key = 'laravel';

        $response = $this->post(route('createshortlink'), [
            'long_url'       => $url->long_url,
            'custom_url_key' => $custom_url_key,
        ]);
        $response->assertRedirect(
            route('short_url.stats', $url->url_key)
        );

        $response2 = $this->get(route('home').'/'.$custom_url_key);
        $response2->assertNotFound();
    }

    /** @test */
    public function cst_long_url_already_exist_2()
    {
        $url = factory(Url::class)->create([
            'user_id' => null,
        ]);

        $this->loginAsNonAdmin();

        $custom_url_key = 'laravel';

        $response = $this->post(route('createshortlink'), [
            'long_url'       => $url->long_url,
            'custom_url_key' => $custom_url_key,
        ]);

        $response->assertRedirect(
            route('short_url.stats', $custom_url_key)
        );

        $response2 = $this->get(route('home').'/'.$custom_url_key);
        $response2->assertRedirect($url->long_url);

        $this->assertCount(2, Url::all());
    }

    /** @test */
    public function cst_cst_url_key_already_exist()
    {
        $url = factory(Url::class)->create();

        $response = $this->post(route('createshortlink'), [
            'long_url'       => 'https://laravel-news.com',
            'custom_url_key' => $url->url_key,
        ]);

        $response
            ->assertRedirect(route('home'))
            ->assertSessionHasErrors('custom_url_key');

        $this->assertCount(1, Url::all());
    }

    /**
     * With authenticated user.
     *
     * @test
     */
    public function cst_cst_url_key_already_exist_2()
    {
        $url = factory(Url::class)->create();

        $this->loginAsNonAdmin();

        $response = $this->post(route('createshortlink'), [
            'long_url'       => 'https://laravel-news.com',
            'custom_url_key' => $url->url_key,
        ]);

        $response
            ->assertRedirect(route('home'))
            ->assertSessionHasErrors('custom_url_key');

        $this->assertCount(1, Url::all());
    }
}
