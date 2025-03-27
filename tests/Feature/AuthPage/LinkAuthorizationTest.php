<?php

namespace Tests\Feature\AuthPage;

use App\Models\Url;
use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\Support\Helper;
use Tests\TestCase;

#[PHPUnit\Group('auth-page')]
#[PHPUnit\Group('link-page')]
class LinkAuthorizationTest extends TestCase
{
    /**
     * Test that an authorized user can access the edit page.
     *
     * @see App\Http\Controllers\UrlController::edit()
     */
    #[PHPUnit\Test]
    public function canAccessEditLinkPage(): void
    {
        $url = Url::factory()->create();
        $response = $this->actingAs($url->author)
            ->get(route('link.edit', $url->keyword));
        $response->assertOk();
    }

    #[PHPUnit\Test]
    #[PHPUnit\DataProvider('canAccessAnotherUsersEditLinkPageProvider')]
    public function canAccessAnotherUsersEditLinkPage($url, $actingAs, $expectedStatus): void
    {
        $url = $url($this);
        $response = $this->actingAs($actingAs($this))
            ->get(route('link.edit', $url->keyword));
        $response->assertStatus($expectedStatus);
    }

    public static function canAccessAnotherUsersEditLinkPageProvider(): array
    {
        return [
            'Admin can access another users link edit page' => [
                'url' => fn(TestCase $tc) => Url::factory()->create(),
                'actingAs' => fn(TestCase $tc) => $tc->adminUser(),
                'expectedStatus' => 200,
            ],
            'Admin can access guest users link edit page' => [
                'url' => fn(TestCase $tc) => Url::factory()->guest()->create(),
                'actingAs' => fn(TestCase $tc) => $tc->adminUser(),
                'expectedStatus' => 200,
            ],
            'Basic users cant access other users link edit page' => [
                'url' => fn(TestCase $tc) => Url::factory()->create(),
                'actingAs' => fn(TestCase $tc) => $tc->basicUser(),
                'expectedStatus' => 403,
            ],
        ];
    }

    #[PHPUnit\Test]
    #[PHPUnit\DataProvider('canUpdateAnotherUsersLinkProvider')]
    public function canUpdateAnotherUsersLink($url, $actingAs, $expectedUpdate): void
    {
        $url = $url($this);
        $newLongUrl = 'https://phpunit.readthedocs.io/en/9.1';

        $response = $this->actingAs($actingAs($this))
            ->from(route('link.edit', $url->keyword))
            ->post(
                route('link.update', $url->keyword),
                Helper::updateLinkData($url, ['long_url' => $newLongUrl]),
            );

        if ($expectedUpdate) {
            $response
                ->assertRedirectToRoute('link.edit', $url->keyword)
                ->assertSessionHas('flash_success');
            $this->assertSame($newLongUrl, $url->fresh()->destination);
        } else {
            $response->assertForbidden();
            $this->assertNotSame($newLongUrl, $url->fresh()->destination);
        }
    }

    public static function canUpdateAnotherUsersLinkProvider(): array
    {
        return [
            'Admin can update another users link' => [
                'url' => fn(TestCase $tc) => Url::factory()->create(),
                'actingAs' => fn(TestCase $tc) => $tc->adminUser(),
                'expectedUpdate' => true,
            ],
            'Admin can update guest users link' => [
                'url' => fn(TestCase $tc) => Url::factory()->guest()->create(),
                'actingAs' => fn(TestCase $tc) => $tc->adminUser(),
                'expectedUpdate' => true,
            ],
            'Basic user cant update other users link' => [
                'url' => fn(TestCase $tc) => Url::factory()->create(),
                'actingAs' => fn(TestCase $tc) => $tc->basicUser(),
                'expectedUpdate' => false,
            ],
        ];
    }

    /**
     * Test that an admin user can delete another user's link.
     *
     * @see App\Http\Controllers\UrlController::delete()
     */
    #[PHPUnit\Test]
    public function adminCanDeleteAnotherUsersLink(): void
    {
        $url = Url::factory()->create();
        $response = $this->actingAs($this->adminUser())
            ->from(route('dboard.allurl'))
            ->get(route('link.delete', $url->keyword));

        $response->assertRedirectToRoute('dboard.allurl')
            ->assertSessionHas('flash_success');
        $this->assertCount(0, Url::all());
    }

    /**
     * Normal users can't delete other users' URLs.
     *
     * @see App\Http\Controllers\UrlController::delete()
     */
    #[PHPUnit\Test]
    public function basicUserCantDeleteAnotherUsersLink(): void
    {
        $url = Url::factory()->create();
        $response = $this->actingAs($this->basicUser())
            ->from(route('dboard.allurl'))
            ->get(route('link.delete', $url->keyword));

        $response->assertForbidden();
        $this->assertCount(1, Url::all());
    }

    #[PHPUnit\Test]
    public function password_create_userCanAccessPage()
    {
        $url = Url::factory()->create();
        $this->actingAs($url->author)
            ->get(route('link.password.create', $url))
            ->assertSuccessful();
    }

    #[PHPUnit\Test]
    public function password_create_adminCanAccessAllPage()
    {
        $url = Url::factory()->create();
        $this->actingAs($this->adminUser())
            ->get(route('link.password.create', $url))
            ->assertSuccessful();
    }

    #[PHPUnit\Test]
    public function password_create_otherUserCantAccessPage()
    {
        $url = Url::factory()->create();
        $this->actingAs($this->basicUser())
            ->get(route('link.password.create', $url))
            ->assertForbidden();
    }

    #[PHPUnit\Test]
    public function password_edit_userCanAccessPage()
    {
        $url = Url::factory()->create(['password' => 'password']);
        $this->actingAs($url->author)
            ->get(route('link.password.edit', $url))
            ->assertSuccessful();
    }

    #[PHPUnit\Test]
    public function password_edit_adminCanAccessAllPage()
    {
        $url = Url::factory()->create(['password' => 'password']);
        $this->actingAs($this->adminUser())
            ->get(route('link.password.edit', $url))
            ->assertSuccessful();
    }

    #[PHPUnit\Test]
    public function password_edit_otherUserCantAccessPage()
    {
        $url = Url::factory()->create(['password' => 'password']);
        $this->actingAs($this->basicUser())
            ->get(route('link.password.edit', $url))
            ->assertForbidden();
    }
}
