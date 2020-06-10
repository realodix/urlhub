<?php

namespace Tests\Feature;

use App\Url;
use Tests\TestCase;

/**
 * Back-End Test.
 */
class UrlBeTest extends TestCase
{
    protected function hashIdRoute($rout_name, $url_id)
    {
        return route($rout_name, \Hashids::connection(\App\Url::class)->encode($url_id));
    }

    /*
    |--------------------------------------------------------------------------
    | Dashboard Page
    |--------------------------------------------------------------------------
    */

    /**
     * @test
     * @group f-dashboard
     */
    public function d_can_access_page()
    {
        $this->loginAsAdmin();

        $response = $this->get(route('dashboard'));
        $response->assertOk();
    }

    /**
     * @test
     * @group f-dashboard
     */
    public function d_can_delete()
    {
        $url = factory(Url::class)->create([
            'user_id' => $this->admin()->id,
        ]);

        $this->loginAsAdmin();

        $response =
            $this
                ->from(route('dashboard'))
                ->get($this->hashIdRoute('dashboard.delete', $url->id));

        $response
            ->assertRedirect(route('dashboard'))
            ->assertSessionHas('flash_success');

        $this->assertCount(0, Url::all());
    }

    /**
     * @test
     * @group f-dashboard
     */
    public function d_can_duplicate()
    {
        $user_id = $this->admin()->id;

        $url = factory(Url::class)->create([
            'user_id' => $this->admin()->id,
        ]);

        $this->loginAsAdmin();

        $response =
            $this
                ->from(route('dashboard'))
                ->get(route('dashboard.duplicate', $url->keyword));

        $response
            ->assertRedirect(route('dashboard'))
            ->assertSessionHas('flash_success');

        $this->assertCount(2, Url::all());
    }

    /**
     * @test
     * @group f-dashboard
     */
    public function d_authorized_user_can_access_edit_url_page()
    {
        $url = factory(Url::class)->create([
            'user_id' => $this->admin()->id,
        ]);

        $this->loginAsAdmin();

        $response = $this->get(route('short_url.edit', $url->keyword));
        $response->assertOk();
    }

    /**
     * @test
     * @group f-dashboard
     */
    public function d_can_update_url()
    {
        $url = factory(Url::class)->create([
            'user_id' => $this->admin()->id,
        ]);

        $new_long_url = 'https://phpunit.readthedocs.io/en/9.1';

        $this->loginAsAdmin();

        $response =
            $this
                ->from(route('short_url.edit', $url->keyword))
                ->post(route('short_url.edit.post', \Hashids::connection(\App\Url::class)->encode($url->id)), [
                    'meta_title' => $url->meta_title,
                    'long_url'   => $new_long_url,
                ]);

        $response
            ->assertRedirect(route('dashboard'))
            ->assertSessionHas('flash_success');

        $this->assertSame($new_long_url, $url->fresh()->long_url);
    }

    /*
    |--------------------------------------------------------------------------
    | All URLs Page
    |--------------------------------------------------------------------------
    */

    /**
     * @test
     * @group f-allurl
     */
    public function au_admin_can_access_this_page()
    {
        $this->loginAsAdmin();

        $response = $this->get(route('dashboard.allurl'));
        $response->assertOk();
    }

    /**
     * @test
     * @group f-allurl
     */
    public function au_non_admin_cant_access_this_page()
    {
        $this->loginAsUser();

        $response = $this->get(route('dashboard.allurl'));
        $response->assertForbidden();
    }

    /**
     * @test
     * @group f-allurl
     */
    public function au_admin_can_delete()
    {
        $url = factory(Url::class)->create();

        $this->loginAsAdmin();

        $response = $this->from(route('dashboard.allurl'))
                         ->get($this->hashIdRoute('dashboard.allurl.delete', $url->id));

        $response
            ->assertRedirect(route('dashboard.allurl'))
            ->assertSessionHas('flash_success');

        $this->assertCount(0, Url::all());
    }

    /**
     * @test
     * @group f-allurl
     */
    public function au_non_admin_cant_delete()
    {
        $url = factory(Url::class)->create();

        $this->loginAsUser();

        $response =
            $this
                ->from(route('dashboard.allurl'))
                ->get($this->hashIdRoute('dashboard.allurl.delete', $url->id));
        $response->assertForbidden();

        $this->assertCount(1, Url::all());
    }

    /*
    |--------------------------------------------------------------------------
    | All Users Page
    |--------------------------------------------------------------------------
    */

    /**
     * @test
     * @group f-alluser
     */
    public function aus_admin_can_access_this_page()
    {
        $this->loginAsAdmin();

        $response = $this->get(route('user.index'));
        $response->assertOk();
    }

    /**
     * @test
     * @group f-alluser
     */
    public function aus_non_admin_cant_access_this_page()
    {
        $this->loginAsUser();

        $response = $this->get(route('user.index'));
        $response->assertForbidden();
    }

    /*
    |--------------------------------------------------------------------------
    | Statistics Page
    |--------------------------------------------------------------------------
    */

    /**
     * @test
     * @group f-stat
     */
    public function stat_admin_can_access_this_page()
    {
        $this->loginAsAdmin();

        $response = $this->get(route('dashboard.stat'));
        $response->assertOk();
    }

    /**
     * @test
     * @group f-stat
     */
    public function stat_non_admin_cant_access_this_page()
    {
        $this->loginAsUser();

        $response = $this->get(route('dashboard.stat'));
        $response->assertForbidden();
    }
}
