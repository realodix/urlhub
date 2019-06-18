<?php

namespace Tests\Unit\Controllers;

use App\Rules\LowercaseRule;
use App\Rules\ShortUrlProtected;
use App\Url;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

/*
 * App\Http\Controllers\UrlController
 */
class UrlControllerTest extends TestCase
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

        $this->assertDatabaseHas('urls', [
            'user_id'   => null,
            'long_url'  => $long_url,
            'is_custom' => 0,
        ]);
    }

    /**
     * With authenticated user.
     *
     * @test
     */
    public function create_2()
    {
        $this->loginAsAdmin();

        $user = $this->admin();
        $long_url = 'https://laravel.com';
        $response = $this->post(route('createshortlink'), [
            'long_url' => $long_url,
        ]);

        $url = Url::whereLongUrl($long_url)->first();

        $response->assertRedirect(route('short_url.stats', $url->url_key));

        $this->assertDatabaseHas('urls', [
            'user_id'  => $user->id,
            'long_url' => $long_url,
            'is_custom' => 0,
        ]);
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

        $this->assertDatabaseHas('urls', [
            'long_url'  => $long_url,
            'url_key'   => $custom_url_key,
            'is_custom' => 1,
        ]);
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

        $this->assertDatabaseHas('urls', [
            'user_id'   => $user->id,
            'long_url'  => $long_url,
            'url_key'   => $custom_url_key,
            'is_custom' => 1,
        ]);
    }

    /** @test */
    public function url_redirection()
    {
        $long_url = 'https://laravel.com';

        $this->post(route('createshortlink'), [
            'long_url' => $long_url,
        ]);

        $url = Url::whereLongUrl($long_url)->first();

        $response = $this->get(route('home').'/'.$url->url_key);
        $response->assertRedirect($long_url);
        $response->assertStatus(301);
    }

    /**
     * With custom URL.
     *
     * @test
     */
    public function url_redirection_2()
    {
        $long_url = 'https://laravel.com';
        $custom_url_key = 'laravel';

        $this->post(route('createshortlink'), [
            'long_url'       => $long_url,
            'custom_url_key' => $custom_url_key,
        ]);

        $response = $this->get(route('home').'/'.$custom_url_key);
        $response->assertRedirect($long_url);
        $response->assertStatus(301);
    }

    /** @test */
    public function check_existing_custom_url_pass()
    {
        $response = $this->post(route('home').'/custom-link-avail-check', [
            'url_key' => 'hello',
        ]);

        $response->assertJson(['success'=>'Available']);
    }

    /**
     * @test
     * @dataProvider checkExistingCustomUrl_fail
     */
    public function check_existing_custom_url_fail($data)
    {
        factory(Url::class)->create([
            'user_id' => null,
            'url_key' => 'laravel'
        ]);

        $request = new Request;

        $validator = Validator::make($request->all(), [
            'url_key'  => ['max:20', 'alpha_dash', 'unique:urls', new LowercaseRule, new ShortUrlProtected],
        ]);

        $response = $this->post(route('home').'/custom-link-avail-check', [
            'url_key' => $data,
        ]);

        $response->assertJson(['errors'=>$validator->errors()->all()]);
    }

    public function checkExistingCustomUrl_fail()
    {
        return [
            [str_repeat('a', 50)],
            ['laravel~'],
            ['laravel'],
            ['Laravel'],
            ['login'],
        ];
    }
}
