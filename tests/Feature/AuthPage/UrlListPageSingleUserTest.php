<?php

namespace Tests\Feature\AuthPage;

use App\Models\User;
use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

#[PHPUnit\Group('auth-page')]
#[PHPUnit\Group('link-page')]
class UrlListPageSingleUserTest extends TestCase
{
    /**
     * Admin can access user and anyone's links table page.
     *
     * @see \App\Http\Controllers\Dashboard\DashboardController::userLinkView()
     */
    #[PHPUnit\Test]
    public function access_LinkTable_SpecificUser_AdminCanAccess(): void
    {
        $user = $this->adminUser();
        $response = $this->actingAs($user)
            ->get(route('dboard.allurl.u-user', $user->name));
        $response->assertOk();

        $response = $this->actingAs($user)
            ->get(route('dboard.allurl.u-user', $this->basicUser()->name));
        $response->assertOk();
    }

    /**
     * Basic users cannot access the "All links" page, even though it specifically
     * contains only their links.
     *
     * @see \App\Http\Controllers\Dashboard\DashboardController::userLinkView()
     */
    #[PHPUnit\Test]
    public function access_LinkTable_SpecificUser_BasicUserCantAccess(): void
    {
        $user = $this->basicUser();
        $response = $this->actingAs($user)
            ->get(route('dboard.allurl.u-user', $user->name));
        $response->assertForbidden();

        $user_2 = User::factory()->create();
        $response = $this->actingAs($user)
            ->get(route('dboard.allurl.u-user', $user_2->name));
        $response->assertForbidden();
    }

    /**
     * Test that an admin user can access the URL list page of a guest user.
     *
     * @see \App\Http\Controllers\Dashboard\DashboardController::guestLinkView()
     */
    #[PHPUnit\Test]
    public function access_LinkTable_Guest_AdminCanAccess(): void
    {
        $response = $this->actingAs($this->adminUser())
            ->get(route('dboard.allurl.u-user', User::GUEST_NAME));
        $response->assertOk();
    }

    /**
     * Non admin users can't access guest links table page.
     *
     * @see \App\Http\Controllers\Dashboard\DashboardController::guestLinkView()
     */
    #[PHPUnit\Test]
    public function access_LinkTable_Guest_BasicUsersCantAccess(): void
    {
        $response = $this->actingAs($this->basicUser())
            ->get(route('dboard.allurl.u-user', User::GUEST_NAME));
        $response->assertForbidden();
    }

    /**
     * Non admin users can't access restricted links table page.
     *
     * @see \App\Http\Controllers\Dashboard\DashboardController::restrictedLinkView()
     */
    #[PHPUnit\Test]
    public function access_RestrictedLinkView_BasicUsersCantAccess(): void
    {
        $user = $this->basicUser();
        $response = $this->actingAs($user)
            ->get(route('dboard.links.restricted'));

        $response->assertForbidden();
    }

    /**
     * Admin can access User-restricted links table page.
     *
     * @see \App\Http\Controllers\Dashboard\DashboardController::userRestrictedLinkView()
     */
    #[PHPUnit\Test]
    public function access_UserRestrictedLinkView_AdminCanAccess(): void
    {
        $user = $this->adminUser();
        $response = $this->actingAs($user)
            ->get(route('dboard.links.user.restricted', $user->name));

        $response->assertOk();
    }

    /**
     * Non admin users can't access User-restricted links table page.
     *
     * @see \App\Http\Controllers\Dashboard\DashboardController::userRestrictedLinkView()
     */
    #[PHPUnit\Test]
    public function access_UserRestrictedLinkView_BasicUsersCantAccess(): void
    {
        $user = $this->basicUser();
        $response = $this->actingAs($user)
            ->get(route('dboard.links.user.restricted', $this->adminUser()->name));

        $response->assertForbidden();
    }
}
