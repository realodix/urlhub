<?php

namespace Tests\Feature;

use App\Url;
use Tests\TestCase;

class UrlBeTest extends TestCase
{
    /**
     * Dashboard Page.
     */

    /** @test */
    public function can_access_dashboard_page()
    {
        $this->loginAsAdmin();

        $response = $this->get(route('dashboard'));
        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_duplicate_own_short_url()
    {
        $long_url = 'https://laravel.com';

        factory(Url::class)->create([
            'user_id'  => $this->user()->id,
            'long_url' => $long_url,
        ]);

        $this->loginAsAdmin();

        $url = Url::whereUserId($this->user()->id)->first();

        $response = $this->from(route('dashboard'))
                         ->get(route('dashboard.duplicate', $url->url_key));

        $count = Url::where('long_url', '=', $long_url)->count();
        $this->assertSame(2, $count);
    }

    // /** @test */
    // public function user_can_duplicate()
    // {
    //     $this->loginAsUser();

    //     $long_url = 'https://laravel.com';

    //     factory(Url::class)->create([
    //         'user_id'  => $this->user()->id,
    //         'long_url' => $long_url,
    //     ]);

    //     $this->post(route('createshortlink'), [
    //         'long_url' => $long_url,
    //     ]);

    //     $url = Url::whereUserId($this->user()->id)->first();

    //     $response = $this->from(route('home').'/+'.$url->url_key)
    //                      ->get(route('duplicate', $url->url_key));

    //     $count = Url::where('long_url', '=', $long_url)->count();
    //     $this->assertSame(2, $count);
    // }

    /**
     * All URLs Page.
     */

    /** @test */
    public function admin_can_access_allurl_page()
    {
        $this->loginAsAdmin();

        $response = $this->get(route('dashboard.allurl'));
        $response->assertStatus(200);
    }

    /** @test */
    public function user_cant_access_allurl_page()
    {
        $this->loginAsUser();

        $response = $this->get(route('dashboard.allurl'));
        $response->assertStatus(403);
    }
}
