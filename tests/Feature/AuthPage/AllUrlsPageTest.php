<?php

namespace Tests\Feature\AuthPage;

use App\Models\Url;
use Tests\TestCase;

class AllUrlsPageTest extends TestCase
{
    protected function secureRoute($routeName, $url_id)
    {
        return route($routeName, encrypt($url_id));
    }

    /**
     * @test
     * @group f-allurl
     */
    public function auAdminCanAccessThisPage()
    {
        $response = $this->actingAs($this->adminUser())
            ->get(route('dashboard.allurl'));

        $response->assertOk();
    }

    /**
     * @test
     * @group f-allurl
     */
    public function auNormalUserCantAccessThisPage()
    {
        $response = $this->actingAs($this->normalUser())
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

        $response = $this->actingAs($this->adminUser())
            ->from(route('dashboard.allurl'))
            ->get($this->secureRoute('dashboard.allurl.su_delete', $url->id));

        $response->assertRedirectToRoute('dashboard.allurl')
            ->assertSessionHas('flash_success');

        $this->assertCount(0, Url::all());
    }

    /**
     * @test
     * @group f-allurl
     */
    public function auNormalUserCantDelete()
    {
        $url = Url::factory()->create();

        $response = $this->actingAs($this->normalUser())
            ->from(route('dashboard.allurl'))
            ->get($this->secureRoute('dashboard.allurl.su_delete', $url->id));

        $response->assertForbidden();
        $this->assertCount(1, Url::all());
    }
}
