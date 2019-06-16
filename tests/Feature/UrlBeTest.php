<?php

namespace Tests\Feature;

use App\Url;
use Tests\TestCase;

/**
 * Back-End Test.
 */
class UrlBeTest extends TestCase
{
    protected function getDeleteRoute($value, $route = 0)
    {
        return route('dashboard.delete', \Hashids::connection(\App\Url::class)->encode($value));
    }

    protected function getAuDeleteRoute($value, $route = 0)
    {
        return route('dashboard.allurl.delete', \Hashids::connection(\App\Url::class)->encode($value));
    }

    /**
     * Dashboard Page.
     */

    /** @test */
    public function d_can_access_page()
    {
        $this->loginAsAdmin();

        $response = $this->get(route('dashboard'));
        $response->assertOk();
    }

    /** @test */
    public function d_can_delete()
    {
        $user_id = $this->admin()->id;

        factory(Url::class)->create([
            'user_id'  => $user_id,
        ]);

        $this->loginAsAdmin();

        $url = Url::whereUserId($user_id)->first();

        $response = $this->from(route('dashboard'))
                         ->get($this->getDeleteRoute($url->id));

        $response
            ->assertRedirect(route('dashboard'))
            ->assertSessionHas('flash_success');

        $this->assertCount(0, Url::all());
    }

    /** @test */
    public function d_can_duplicate()
    {
        $user_id = $this->admin()->id;

        factory(Url::class)->create([
            'user_id'  => $user_id,
        ]);

        $this->loginAsAdmin();

        $url = Url::whereUserId($user_id)->first();

        $response = $this->from(route('dashboard'))
                         ->get(route('dashboard.duplicate', $url->url_key));

        $response
            ->assertRedirect(route('dashboard'))
            ->assertSessionHas('flash_success');

        $this->assertCount(2, Url::all());
    }

    /**
     * All URLs Page.
     */

    /** @test */
    public function au_admin_can_access_this_page()
    {
        $this->loginAsAdmin();

        $response = $this->get(route('dashboard.allurl'));
        $response->assertOk();
    }

    /** @test */
    public function au_non_admin_cant_access_this_page()
    {
        $this->loginAsNonAdmin();

        $response = $this->get(route('dashboard.allurl'));
        $response->assertForbidden();
    }

    /** @test */
    public function au_admin_can_delete()
    {
        $user_id = $this->nonAdmin()->id;

        factory(Url::class)->create([
            'user_id'  => $user_id,
        ]);

        $this->loginAsAdmin();

        $url = Url::whereUserId($user_id)->first();

        $response = $this->from(route('dashboard.allurl'))
                         ->get($this->getAuDeleteRoute($url->id));

        $response
            ->assertRedirect(route('dashboard.allurl'))
            ->assertSessionHas('flash_success');

        $this->assertCount(0, Url::all());
    }

    /** @test */
    public function au_non_admin_cant_delete()
    {
        $user_id = $this->admin()->id;

        factory(Url::class)->create([
            'user_id'  => $user_id,
        ]);

        $this->loginAsNonAdmin();

        $url = Url::whereUserId($user_id)->first();

        $response = $this->from(route('dashboard.allurl'))
                         ->get($this->getAuDeleteRoute($url->id));
        $response->assertForbidden();

        $this->assertCount(1, Url::all());
    }
}
