<?php

namespace Tests\Feature;

use App\Models\Url;
use Tests\TestCase;
use Vinkla\Hashids\Facades\Hashids;

/**
 * Back-End Test.
 */
class UrlBeTest extends TestCase
{
    protected function hashIdRoute($routeName, $url_id)
    {
        $hashids = Hashids::connection(\App\Models\Url::class);

        return route($routeName, $hashids->encode($url_id));
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
        $response = $this->actingAs($this->admin())
            ->get(route('dashboard'));

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

        $response = $this->actingAs($this->admin())
            ->from(route('dashboard'))
            ->get($this->hashIdRoute('dashboard.url_delete', $url->id));

        $response
            ->assertRedirectToRoute('dashboard')
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

        $response = $this->actingAs($this->admin())
            ->from(route('dashboard'))
            ->get(route('dashboard.url_duplicate', $url->keyword));

        $response
            ->assertRedirectToRoute('dashboard')
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

        $response = $this->actingAs($this->admin())
            ->get(route('dashboard.url_edit', $url->keyword));

        $response->assertOk();
    }

    /**
     * @test
     * @group f-dashboard
     */
    public function dCanUpdateUrl()
    {
        $hashids = Hashids::connection(\App\Models\Url::class);
        $url = Url::factory()->create([
            'user_id' => $this->admin()->id,
        ]);

        $new_long_url = 'https://phpunit.readthedocs.io/en/9.1';

        $response = $this->actingAs($this->admin())
            ->from(route('dashboard.url_edit', $url->keyword))
            ->post(route('dashboard.url_edit.post', $hashids->encode($url->id)), [
                'meta_title' => $url->meta_title,
                'long_url'   => $new_long_url,
            ]);

        $response
            ->assertRedirectToRoute('dashboard')
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
        $response = $this->actingAs($this->admin())
            ->get(route('dashboard.allurl'));

        $response->assertOk();
    }

    /**
     * @test
     * @group f-allurl
     */
    public function auNonAdminCantAccessThisPage()
    {
        $response = $this->actingAs($this->nonAdmin())
            ->get(route('dashboard.allurl'));

        $response->assertForbidden();
    }

    /**
     * @test
     * @group f-allurl
     */
    public function auAdminCanDelete()
    {
        $url = Url::factory()->create();

        $response = $this->actingAs($this->admin())
            ->from(route('dashboard.allurl'))
            ->get($this->hashIdRoute('dashboard.allurl.delete', $url->id));

        $response->assertRedirectToRoute('dashboard.allurl')
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

        $response = $this->actingAs($this->nonAdmin())
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
        $response = $this->actingAs($this->admin())
            ->get(route('user.index'));

        $response->assertOk();
    }

    /**
     * @test
     * @group f-alluser
     */
    public function ausNonAdminCantAccessThisPage()
    {
        $response = $this->actingAs($this->nonAdmin())
            ->get(route('user.index'));

        $response->assertForbidden();
    }
}
