<?php

namespace Tests\Feature\AuthPage;

use App\Models\Url;
use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

#[PHPUnit\Group('auth-page')]
#[PHPUnit\Group('link-page')]
class AllUrlsPageTest extends TestCase
{
    #[PHPUnit\Test]
    public function auAdminCanAccessThisPage(): void
    {
        $response = $this->actingAs($this->adminUser())
            ->get(route('dashboard.allurl'));
        $response->assertOk();
    }

    #[PHPUnit\Test]
    public function auNormalUserCantAccessThisPage(): void
    {
        $response = $this->actingAs($this->normalUser())
            ->get(route('dashboard.allurl'));
        $response->assertForbidden();
    }

    /**
     * Admin can access user links and guest links table page
     */
    public function testAdminCanAccessUserLinksTablePage(): void
    {
        $user = $this->adminUser();

        $response = $this->actingAs($user)
            ->get(route('dashboard.allurl.u-user', $user->name));
        $response->assertOk();

        $response = $this->actingAs($user)
            ->get(route('dashboard.allurl.u-guest'));
        $response->assertOk();
    }

    /**
     * Non admin users can't access user links and guest links table page
     */
    public function testNonAdminUsersCantAccessUserLinksTablePage(): void
    {
        $user = $this->normalUser();

        $response = $this->actingAs($user)
            ->get(route('dashboard.allurl.u-user', $this->adminUser()->name));
        $response->assertForbidden();

        $response = $this->actingAs($user)
            ->get(route('dashboard.allurl.u-guest'));
        $response->assertForbidden();
    }

    #[PHPUnit\Test]
    public function auAdminCanDelete(): void
    {
        $url = Url::factory()->create();

        $response = $this->actingAs($this->adminUser())
            ->from(route('dashboard.allurl'))
            ->get(route('dboard.url.delete', $url->keyword));

        $response->assertRedirectToRoute('dashboard.allurl')
            ->assertSessionHas('flash_success');

        $this->assertCount(0, Url::all());
    }

    #[PHPUnit\Test]
    public function auNormalUserCantDelete(): void
    {
        $url = Url::factory()->create();

        $response = $this->actingAs($this->normalUser())
            ->from(route('dashboard.allurl'))
            ->get(route('dboard.url.delete', $url->keyword));

        $response->assertForbidden();
        $this->assertCount(1, Url::all());
    }
}
