<?php

namespace Tests\Unit\Controllers;

use App\Rules\Lowercase;
use App\Rules\URL\ShortUrlProtected;
use App\Url;
use App\UrlStat;
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
        $url = factory(Url::class)->create();

        $response = $this->get(route('home').'/'.$url->url_key);
        $response->assertRedirect($url->long_url);
        $response->assertStatus(301);
    }

    /**
     * URL statistic check.
     *
     * @test
     */
    public function url_redirection_2()
    {
        $url = factory(Url::class)->create();

        $response = $this->get(route('home').'/'.$url->url_key);
        $this->assertCount(1, UrlStat::all());
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
