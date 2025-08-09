<?php

namespace Tests\Feature\AuthPage;

use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

#[PHPUnit\Group('auth-page')]
#[PHPUnit\Group('link-page')]
class UrlListPageTest extends TestCase
{
    /**
     * Admin users can access the link table page.
     *
     * @see App\Http\Controllers\Dashboard\DashboardController::allUrlView()
     */
    #[PHPUnit\Test]
    public function adminCasAccessLinkTablePage(): void
    {
        $response = $this->actingAs($this->adminUser())
            ->get(route('dboard.allurl'));
        $response->assertOk();
    }

    /**
     * Normal users can't access the link table page.
     *
     * @see App\Http\Controllers\Dashboard\DashboardController::allUrlView()
     */
    #[PHPUnit\Test]
    public function basicUserCantAccessLinkTablePage(): void
    {
        $response = $this->actingAs($this->basicUser())
            ->get(route('dboard.allurl'));
        $response->assertForbidden();
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
     * Admin can access restricted links table page.
     *
     * @see App\Http\Controllers\Dashboard\DashboardController::restrictedLinkView()
     */
    #[PHPUnit\Test]
    public function restrictedLinkView_admin(): void
    {
        $user = $this->adminUser();
        $response = $this->actingAs($user)
            ->get(route('dboard.links.restricted'));

        $response->assertOk();
    }
}
