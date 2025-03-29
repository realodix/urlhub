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
            ->from(route('dashboard'))
            ->get(route('link.delete', $url->keyword));

        $response
            ->assertRedirectToRoute('dashboard')
            ->assertSessionHas('flash_success');
        $this->assertCount(0, Url::all());
    }
}
