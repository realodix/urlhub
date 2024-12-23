<?php

namespace Tests\Feature\AuthPage;

use App\Models\Url;
use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

#[PHPUnit\Group('auth-page')]
#[PHPUnit\Group('link-page')]
class UrlListPageTest extends TestCase
{
    /**
     * Admin users can access the link table page.
     *
     * @see App\Http\Controllers\Dashboard\AllUrlController::view()
     */
    #[PHPUnit\Test]
    public function adminCasAccessLinkTablePage(): void
    {
        $response = $this->actingAs($this->adminUser())
            ->get(route('dashboard.allurl'));
        $response->assertOk();
    }

    /**
     * Normal users can't access the link table page.
     *
     * @see App\Http\Controllers\Dashboard\AllUrlController::view()
     */
    #[PHPUnit\Test]
    public function basicUserCantAccessLinkTablePage(): void
    {
        $response = $this->actingAs($this->basicUser())
            ->get(route('dashboard.allurl'));
        $response->assertForbidden();
    }

    /**
     * Test that an admin user can delete another user's link.
     *
     * This test simulates an admin user trying to delete a link of another user,
     * verifies that the link is deleted by counting the number of links in the
     * database, and confirms that the operation is successful by checking for
     * a redirect response and a success flash message.
     *
     * @see App\Http\Controllers\Dashboard\DashboardController::delete()
     */
    #[PHPUnit\Test]
    public function adminCanDelete(): void
    {
        $url = Url::factory()->create();
        $response = $this->actingAs($this->adminUser())
            ->from(route('dashboard.allurl'))
            ->get(route('dboard.url.delete', $url->keyword));

        $response->assertRedirectToRoute('dashboard.allurl')
            ->assertSessionHas('flash_success');
        $this->assertCount(0, Url::all());
    }

    /**
     * Normal users can't delete other users' URLs.
     *
     * @see App\Http\Controllers\Dashboard\DashboardController::delete()
     */
    #[PHPUnit\Test]
    public function basicUserCantDelete(): void
    {
        $url = Url::factory()->create();
        $response = $this->actingAs($this->basicUser())
            ->from(route('dashboard.allurl'))
            ->get(route('dboard.url.delete', $url->keyword));

        $response->assertForbidden();
        $this->assertCount(1, Url::all());
    }

    /**
     * Admin can access another users' link edit page.
     *
     * This test simulates an admin user trying to access the edit page of a link
     * of another user, verifies that the operation is successful by checking for
     * a successful response.
     *
     * @see App\Http\Controllers\Dashboard\DashboardController::edit()
     */
    #[PHPUnit\Test]
    public function adminCanAccessOtherUsersLinkEditPage(): void
    {
        $url = Url::factory()->create();
        $response = $this->actingAs($this->adminUser())
            ->get(route('dboard.url.edit.show', $url->keyword));
        $response->assertOk();
    }

    /**
     * Admin can access guest users' link edit page.
     *
     * This test simulates an admin user trying to access the edit page of a link
     * of a guest user, verifies that the operation is successful by checking for
     * a successful response.
     *
     * @see App\Http\Controllers\Dashboard\DashboardController::edit()
     */
    #[PHPUnit\Test]
    public function adminCanAccessGuestUsersLinkEditPage(): void
    {
        $url = Url::factory()->create(['user_id' => Url::GUEST_ID]);
        $response = $this->actingAs($this->adminUser())
            ->get(route('dboard.url.edit.show', $url->keyword));
        $response->assertOk();
    }

    /**
     * Basic users can't access other users' link edit page.
     *
     * @see App\Http\Controllers\Dashboard\DashboardController::edit()
     */
    #[PHPUnit\Test]
    public function basicUserCantAccessOtherUsersLinkEditPage(): void
    {
        $url = Url::factory()->create();
        $response = $this->actingAs($this->basicUser())
            ->get(route('dboard.url.edit.show', $url->keyword));
        $response->assertForbidden();
    }

    /**
     * Admin can update another user's link.
     *
     * @see App\Http\Controllers\Dashboard\DashboardController::update()
     */
    #[PHPUnit\Test]
    public function adminCanUpdateOtherUsersLink(): void
    {
        $url = Url::factory()->create();
        $newLongUrl = 'https://phpunit.readthedocs.io/en/9.1';
        $response = $this->actingAs($this->adminUser())
            ->from(route('dboard.url.edit.show', $url->keyword))
            ->post(route('dboard.url.edit.store', $url->keyword), [
                'title'    => $url->title,
                'long_url' => $newLongUrl,
            ]);

        $response
            ->assertRedirectToRoute('dashboard')
            ->assertSessionHas('flash_success');
        $this->assertSame($newLongUrl, $url->fresh()->destination);
    }

    /**
     * A normal user can't update another user's link.
     *
     * This test simulates a normal user trying to update the link of another user,
     * verifies that the operation is forbidden by checking for a forbidden response,
     * and confirms that the link is not updated.
     *
     * @see App\Http\Controllers\Dashboard\DashboardController::update()
     */
    #[PHPUnit\Test]
    public function basicUserCantUpdateOtherUsersLink(): void
    {
        $url = Url::factory()->create();
        $newLongUrl = 'https://phpunit.readthedocs.io/en/9.1';
        $response = $this->actingAs($this->basicUser())
            ->from(route('dboard.url.edit.show', $url->keyword))
            ->post(route('dboard.url.edit.store', $url->keyword), [
                'title'    => $url->title,
                'long_url' => $newLongUrl,
            ]);

        $response->assertForbidden();
        $this->assertNotSame($newLongUrl, $url->fresh()->destination);
    }
}
