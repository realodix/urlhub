<?php

namespace Tests\Feature\AuthPage;

use App\Models\Url;
use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

#[PHPUnit\Group('auth-page')]
#[PHPUnit\Group('link-page')]
class UrlListPageSingleUserTest extends TestCase
{
    /**
     * Admin can access user links and guest links table page.
     *
     * @see App\Http\Controllers\Dashboard\DashboardController::userLinkView()
     */
    #[PHPUnit\Test]
    public function adminCanAccessUserLinksTablePage(): void
    {
        $user = $this->adminUser();
        $response = $this->actingAs($user)
            ->get(route('dboard.allurl.u-user', $user->name));

        $response->assertOk();
    }

    /**
     * Non admin users can't access user links and guest links table page.
     *
     * @see App\Http\Controllers\Dashboard\DashboardController::userLinkView()
     */
    #[PHPUnit\Test]
    public function basicUsersCantAccessUserLinksTablePage(): void
    {
        $user = $this->basicUser();
        $response = $this->actingAs($user)
            ->get(route('dboard.allurl.u-user', $this->adminUser()->name));

        $response->assertForbidden();
    }

    /**
     * Test that an admin user can access the URL list page of a guest user.
     *
     * @see App\Http\Controllers\Dashboard\DashboardController::guestLinkView()
     */
    #[PHPUnit\Test]
    public function adminCanAccessTheUrlListPageOfAGuestUser(): void
    {
        $response = $this->actingAs($this->adminUser())
            ->get(route('dboard.allurl.u-guest'));
        $response->assertOk();
    }

    /**
     * Non admin users can't access guest links table page.
     *
     * @see App\Http\Controllers\Dashboard\DashboardController::guestLinkView()
     */
    #[PHPUnit\Test]
    public function basicUsersCantAccessTheUrlListPageOfAGuestUser(): void
    {
        $response = $this->actingAs($this->basicUser())
            ->get(route('dboard.allurl.u-guest'));
        $response->assertForbidden();
    }
}
