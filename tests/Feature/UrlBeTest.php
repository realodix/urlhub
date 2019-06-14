<?php

namespace Tests\Feature;

use App\Url;
use Tests\TestCase;

class UrlBeTest extends TestCase
{
    protected function getDeleteRoute($value)
    {
        return route('dashboard.delete', \Hashids::connection(\App\Url::class)->encode($value));
    }

    /**
     * Dashboard Page.
     */

    /** @test */
    public function d_can_access_page()
    {
        $this->loginAsAdmin();

        $response = $this->get(route('dashboard'));
        $response->assertStatus(200);
    }

    /** @test */
    public function d_can_delete()
    {
        $user_id = $this->admin()->id;
        $long_url = 'https://laravel.com';

        factory(Url::class)->create([
            'user_id'  => $user_id,
            'long_url' => $long_url,
        ]);

        $this->loginAsAdmin();

        $url = Url::whereUserId($user_id)->first();

        $response = $this->from(route('dashboard'))
                         ->get($this->getDeleteRoute($url->id));
        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas(['flash_success']);

        $count = Url::where('long_url', '=', $long_url)->count();
        $this->assertSame(0, $count);
    }

    /** @test */
    public function d_can_duplicate()
    {
        $user_id = $this->admin()->id;
        $long_url = 'https://laravel.com';

        factory(Url::class)->create([
            'user_id'  => $user_id,
            'long_url' => $long_url,
        ]);

        $this->loginAsAdmin();

        $url = Url::whereUserId($user_id)->first();

        $response = $this->from(route('dashboard'))
                         ->get(route('dashboard.duplicate', $url->url_key));
        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas(['flash_success']);

        $count = Url::where('long_url', '=', $long_url)->count();
        $this->assertSame(2, $count);
    }

    /**
     * All URLs Page.
     */

    /** @test */
    public function au_admin_can_access_page()
    {
        $this->loginAsAdmin();

        $response = $this->get(route('dashboard.allurl'));
        $response->assertStatus(200);
    }

    /** @test */
    public function au_user_cant_access_page()
    {
        $this->loginAsNonAdmin();

        $response = $this->get(route('dashboard.allurl'));
        $response->assertStatus(403);
    }

    /** @test */
    public function au_admin_can_delete()
    {
        $user_id = $this->admin()->id;
        $long_url = 'https://laravel.com';

        factory(Url::class)->create([
            'user_id'  => $user_id,
            'long_url' => $long_url,
        ]);

        $this->loginAsAdmin();

        $url = Url::whereUserId($user_id)->first();

        $response = $this->from(route('dashboard.allurl'))
                         ->get($this->getDeleteRoute($url->id));
        $response->assertRedirect(route('dashboard.allurl'));
        $response->assertSessionHas(['flash_success']);

        $count = Url::where('long_url', '=', $long_url)->count();
        $this->assertSame(0, $count);
    }

    /** @test */
    public function au_user_cant_delete()
    {
        $user_id = $this->admin()->id;
        $long_url = 'https://laravel.com';

        factory(Url::class)->create([
            'user_id'  => $user_id,
            'long_url' => $long_url,
        ]);

        $this->loginAsNonAdmin();

        $url = Url::whereUserId($user_id)->first();

        $response = $this->from(route('dashboard.allurl'))
                         ->get($this->getDeleteRoute($url->id));
        $response->assertStatus(403);

        $count = Url::where('long_url', '=', $long_url)->count();
        $this->assertSame(1, $count);
    }
}
