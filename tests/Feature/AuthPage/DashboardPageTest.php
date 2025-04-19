<?php

namespace Tests\Feature\AuthPage;

use App\Models\Url;
use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

#[PHPUnit\Group('auth-page')]
#[PHPUnit\Group('link-page')]
class DashboardPageTest extends TestCase
{
    /**
     * Test that an authenticated user can access the dashboard page.
     *
     * @see App\Http\Controllers\Dashboard\DashboardController::view()
     */
    #[PHPUnit\Test]
    public function canAccessPage(): void
    {
        $response = $this->actingAs($this->basicUser())
            ->get(route('dashboard'));
        $response->assertOk();
    }

    /**
     * Test that an authenticated user can delete a link.
     *
     * @see App\Http\Controllers\LinkController::delete()
     */
    #[PHPUnit\Test]
    public function canDelete(): void
    {
        $url = Url::factory()->create();
        $response = $this->actingAs($url->author)
            ->get(route('link.delete', $url->keyword));

        $response
            ->assertRedirectToRoute('dashboard')
            ->assertSessionHas('flash_success');
        $this->assertCount(0, Url::all());
    }

    /**
     * Test that an authenticated user can delete a link from table.
     *
     * @see App\Http\Controllers\LinkController::delete()
     */
    #[PHPUnit\Test]
    #[PHPUnit\TestWith(['dashboard'])]
    #[PHPUnit\TestWith(['dboard.allurl'])]
    public function canDelete_fromTable($route): void
    {
        $url = Url::factory()->create();
        $response = $this->actingAs($url->author)
            ->from(route($route))
            ->get(route('link.delete.fromTable', $url->keyword));

        $response
            ->assertRedirectToRoute($route)
            ->assertSessionHas('flash_success');
        $this->assertCount(0, Url::all());
    }

    #[PHPUnit\Test]
    public function adminCanAccessOverviewPage(): void
    {
        $response = $this->actingAs($this->adminUser())
            ->get(route('dboard.overview'));
        $response->assertOk();
    }

    #[PHPUnit\Test]
    public function userCantAccessOverviewPage(): void
    {
        $response = $this->actingAs($this->basicUser())
            ->get(route('dboard.overview'));
        $response->assertForbidden();
    }

    #[PHPUnit\Test]
    public function userCanAccesOwnOverviewPage(): void
    {
        $user = $this->basicUser();
        $response = $this->actingAs($user)
            ->get(route('user.overview', $user));
        $response->assertOk();
    }

    #[PHPUnit\Test]
    public function adminCanAccessOtherOverviewPage(): void
    {
        $response = $this->actingAs($this->adminUser())
            ->get(route('user.overview', $this->basicUser()));
        $response->assertOk();
    }

    #[PHPUnit\Test]
    public function userCantAccessOtherOverviewPage(): void
    {
        $user = $this->basicUser();
        $response = $this->actingAs($user)
            ->get(route('user.overview', $this->adminUser()));
        $response->assertForbidden();
    }
}
