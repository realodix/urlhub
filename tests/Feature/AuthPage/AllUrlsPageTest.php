<?php

namespace Tests\Feature\AuthPage;

use App\Models\Url;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AllUrlsPageTest extends TestCase
{
    #[Test]
    #[Group('f-allurl')]
    public function auAdminCanAccessThisPage(): void
    {
        $response = $this->actingAs($this->adminUser())
            ->get(route('dashboard.allurl'));
        $response->assertOk();
    }

    #[Test]
    #[Group('f-allurl')]
    public function auNormalUserCantAccessThisPage(): void
    {
        $response = $this->actingAs($this->normalUser())
            ->get(route('dashboard.allurl'));
        $response->assertForbidden();
    }

    /**
     * Admin can access user links and guest links table page
     */
    #[Group('f-allurl')]
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
    #[Group('f-allurl')]
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

    #[Test]
    #[Group('f-allurl')]
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

    #[Test]
    #[Group('f-allurl')]
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
