<?php

namespace Tests\Unit\Controllers;

use App\Rules\Lowercase;
use App\Rules\URL\ShortUrlProtected;
use App\Url;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

/*
 * App\Http\Controllers\UrlController
 */
class UrlControllerTest extends TestCase
{
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

    /**
     * URL statistic check.
     *
     * @test
     */
    public function url_redirection_3()
    {
        $long_url = 'https://foo.com/bar';

        $this->post(route('createshortlink'), [
            'long_url' => $long_url,
        ]);

        $url = Url::whereLongUrl($long_url)->first();

        $this->get(route('home').'/'.$url->url_key);
        $this->assertDatabaseHas('url_stats', [
            'url_id' => $url->id,
        ]);
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
            'url_key' => 'laravel',
        ]);

        $request = new Request;

        $validator = Validator::make($request->all(), [
            'url_key'  => ['max:20', 'alpha_dash', 'unique:urls', new Lowercase, new ShortUrlProtected],
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
