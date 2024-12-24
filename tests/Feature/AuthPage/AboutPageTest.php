<?php

namespace Tests\Feature\AuthPage;

use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

#[PHPUnit\Group('auth-page')]
class AboutPageTest extends TestCase
{
    /**
     * Test that an admin user can access the about page.
     *
     * This test simulates an admin user trying to access the about page, verifies
     * that the operation is successful by checking for an ok response.
     *
     * @see App\Http\Controllers\Dashboard\DashboardController::aboutView()
     */
    #[PHPUnit\Test]
    public function auAdminCanAccessThisPage(): void
    {
        $response = $this->actingAs($this->adminUser())
            ->get(route('dboard.about'));
        $response->assertOk();
    }

    /**
     * Test that a normal user cannot access the about page.
     *
     * This test simulates a normal user attempting to access the about page
     * and verifies that access is forbidden by checking for a forbidden
     * response.
     *
     * @see App\Http\Controllers\Dashboard\DashboardController::aboutView()
     */
    #[PHPUnit\Test]
    public function auNormalUserCantAccessThisPage(): void
    {
        $response = $this->actingAs($this->basicUser())
            ->get(route('dboard.about'));
        $response->assertForbidden();
    }
}
