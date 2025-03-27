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
}
