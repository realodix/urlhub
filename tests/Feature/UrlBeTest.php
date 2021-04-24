<?php

namespace Tests\Feature;

use App\Models\Url;
use Tests\TestCase;

/**
 * Back-End Test.
 */
class UrlBeTest extends TestCase
{
    protected function hashIdRoute($routeName, $url_id)
    {
        return route($routeName, \Hashids::connection(\App\Models\Url::class)->encode($url_id));
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
    public function dCanAccessPage()
    {
        $this->loginAsAdmin();

        $response = $this->get(route('dashboard'));
        $response->assertOk();
    }

    /**
     * @test
     * @group f-dashboard
     */
    public function dCanDelete()
    {
        $url = Url::factory()->create([
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
    public function dCanDuplicate()
    {
        $url = Url::factory()->create([
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
    public function dAuthorizedUserCanAccessEditUrlPage()
    {
        $url = Url::factory()->create([
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
    public function dCanUpdateUrl()
    {
        $url = Url::factory()->create([
            'user_id' => $this->admin()->id,
        ]);

        $new_long_url = 'https://phpunit.readthedocs.io/en/9.1';

        $this->loginAsAdmin();

        $response =
            $this
                ->from(route('short_url.edit', $url->keyword))
                ->post(route('short_url.edit.post', \Hashids::connection(\App\Models\Url::class)->encode($url->id)), [
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
    public function auAdminCanAccessThisPage()
    {
        $this->loginAsAdmin();

        $response = $this->get(route('dashboard.allurl'));
        $response->assertOk();
    }

    /**
     * @test
     * @group f-allurl
     */
    public function auNonAdminCantAccessThisPage()
    {
        $this->loginAsNonAdmin();

        $response = $this->get(route('dashboard.allurl'));
        $response->assertForbidden();
    }

    /**
     * @test
     * @group f-allurl
     */
    public function auAdminCanDelete()
    {
        $url = Url::factory()->create();

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
    public function auNonAdminCantDelete()
    {
        $url = Url::factory()->create();

        $this->loginAsNonAdmin();

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
    public function ausAdminCanAccessThisPage()
    {
        $this->loginAsAdmin();

        $response = $this->get(route('user.index'));
        $response->assertOk();
    }

    /**
     * @test
     * @group f-alluser
     */
    public function ausNonAdminCantAccessThisPage()
    {
        $this->loginAsNonAdmin();

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
    public function statAdminCanAccessThisPage()
    {
        $this->loginAsAdmin();

        $response = $this->get(route('dashboard.stat'));
        $response->assertOk();
    }

    /**
     * @test
     * @group f-stat
     */
    public function statNonAdminCantAccessThisPage()
    {
        $this->loginAsNonAdmin();

        $response = $this->get(route('dashboard.stat'));
        $response->assertForbidden();
    }
}
