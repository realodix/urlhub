<?php

namespace Tests\Feature\AuthPage;

use App\Http\Requests\StoreUrlRequest;
use App\Models\Url;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\Support\Helper;
use Tests\TestCase;

#[PHPUnit\Group('auth-page')]
#[PHPUnit\Group('link-page')]
class LinkTest extends TestCase
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

    /**
     * Test that an authorized user can update a link.
     *
     * @see App\Http\Controllers\UrlController::update()
     */
    #[PHPUnit\Test]
    public function canUpdateLink(): void
    {
        $url = Url::factory()->create();
        $newLongUrl = 'https://phpunit.readthedocs.io/en/9.1';
        $response = $this->actingAs($url->author)
            ->from(route('link.edit', $url->keyword))
            ->post(
                route('link.update', $url->keyword),
                Helper::updateLinkData($url, ['long_url' => $newLongUrl]),
            );

        $response
            ->assertRedirectToRoute('link.edit', $url->keyword)
            ->assertSessionHas('flash_success');
        $this->assertSame($newLongUrl, $url->fresh()->destination);
    }

    /**
     * @see App\Http\Controllers\UrlController::update()
     */
    public function test_update_validates_title_length(): void
    {
        $url = Url::factory()->create();
        $response = $this->actingAs($url->author)
            ->from(route('link.edit', $url->keyword))
            ->post(
                route('link.update', $url->keyword),
                Helper::updateLinkData($url, [
                    'title' => str_repeat('a', Url::TITLE_LENGTH + 1)],
                ),
            );

        $response
            ->assertRedirect(route('link.edit', $url->keyword))
            ->assertSessionHasErrors('title');
    }

    /**
     * @see App\Http\Controllers\UrlController::update()
     */
    public function test_update_validates_long_url_is_url(): void
    {
        $url = Url::factory()->create();
        $response = $this->actingAs($url->author)
            ->from(route('link.edit', $url->keyword))
            ->post(
                route('link.update', $url->keyword),
                Helper::updateLinkData($url, [
                    'long_url' => 'invalid-url',
                    'dest_android' => 'invalid-url',
                    'dest_ios' => 'invalid-url',
                    'expired_url' => 'invalid-url',
                ]),
            );

        $response
            ->assertRedirect(route('link.edit', $url->keyword))
            ->assertSessionHasErrors(['long_url', 'dest_android', 'dest_ios', 'expired_url']);
    }

    /**
     * @see App\Http\Controllers\UrlController::update()
     */
    public function test_update_validates_long_url_max_length(): void
    {
        $veryLongUrl = 'https://laravel.com/'.str_repeat('a', StoreUrlRequest::URL_LENGTH);

        $url = Url::factory()->create();
        $response = $this->actingAs($url->author)
            ->from(route('link.edit', $url->keyword))
            ->post(
                route('link.update', $url->keyword),
                Helper::updateLinkData($url, [
                    'long_url' => $veryLongUrl,
                    'dest_android' => $veryLongUrl,
                    'dest_ios' => $veryLongUrl,
                    'expired_url' => $veryLongUrl,
                ]),
            );

        $response
            ->assertRedirect(route('link.edit', $url->keyword))
            ->assertSessionHasErrors(['long_url', 'dest_android', 'dest_ios', 'expired_url']);
    }

    /**
     * @see App\Http\Controllers\UrlController::update()
     */
    public function test_update_validates_long_url_not_blacklisted()
    {
        config(['urlhub.domain_blacklist' => ['t.co']]);
        $blacklistedDomain = 'https://t.co/about';
        $url = Url::factory()->create();

        $response = $this->actingAs($url->author)
            ->from(route('link.edit', $url->keyword))
            ->post(
                route('link.update', $url->keyword),
                Helper::updateLinkData($url, [
                    'long_url' => $blacklistedDomain,
                    'dest_android' => $blacklistedDomain,
                    'dest_ios' => $blacklistedDomain,
                    'expired_url' => $blacklistedDomain,
                ]),
            );

        $response
            ->assertRedirect(route('link.edit', $url->keyword))
            ->assertSessionHasErrors(['long_url', 'dest_android', 'dest_ios', 'expired_url']);
    }

    #[PHPUnit\Test]
    public function updateExpiresAt_Valid()
    {
        $url = Url::factory()->create();
        $response = $this->actingAs($url->author)
            ->from(route('link.edit', $url->keyword))
            ->post(
                route('link.update', $url->keyword),
                Helper::updateLinkData($url, ['expires_at' => now()->addDay()->format('Y-m-d')]),
            );

        $response
            ->assertRedirectToRoute('link.edit', $url->keyword)
            ->assertSessionHas('flash_success');
        $this->assertNotNull($url->fresh()->expires_at);

        $response = $this->actingAs($url->author)
            ->from(route('link.edit', $url->keyword))
            ->post(
                route('link.update', $url->keyword),
                Helper::updateLinkData($url, ['expires_at' => null]),
            ); // remove expires_at

        $response
            ->assertRedirectToRoute('link.edit', $url->keyword)
            ->assertSessionHas('flash_success');
        $this->assertNull($url->fresh()->expires_at);
    }

    #[PHPUnit\Test]
    public function updateExpiresAt_Invalid()
    {
        $url = Url::factory()->create();
        $response = $this->actingAs($url->author)
            ->from(route('link.edit', $url->keyword))
            ->post(
                route('link.update', $url->keyword),
                Helper::updateLinkData($url, ['expires_at' => now()->subMinute()]),
            );

        $response
            ->assertRedirectToRoute('link.edit', $url->keyword)
            ->assertSessionHasErrors(['expires_at']);
    }

    public function testUpdateWithNullableValue()
    {
        $url = Url::factory()->create();
        $response = $this->actingAs($url->author)
            ->from(route('link.edit', $url->keyword))
            ->post(
                route('link.update', $url->keyword),
                Helper::updateLinkData($url, [
                    'dest_android' => '',
                    'dest_ios' => '',
                    'expired_url' => '',
                ]),
            );

        $response
            ->assertRedirectToRoute('link.edit', $url->keyword)
            ->assertSessionHas('flash_success');
        $this->assertNull($url->fresh()->dest_android);
        $this->assertNull($url->fresh()->dest_ios);
        $this->assertNull($url->fresh()->expired_url);
    }

    #[PHPUnit\Test]
    public function password_create_userCanAccess()
    {
        $url = Url::factory()->create();
        $this->actingAs($url->author)
            ->get(route('link.password.create', $url))
            ->assertSuccessful();
    }

    #[PHPUnit\Test]
    public function password_create_adminCanAccessAll()
    {
        $url = Url::factory()->create();
        $this->actingAs($this->adminUser())
            ->get(route('link.password.create', $url))
            ->assertSuccessful();
    }

    #[PHPUnit\Test]
    public function password_create_otherUserCantAccess()
    {
        $url = Url::factory()->create();
        $this->actingAs($this->basicUser())
            ->get(route('link.password.create', $url))
            ->assertForbidden();
    }

    #[PHPUnit\Test]
    public function password_edit_userCanAccess()
    {
        $url = Url::factory()->create(['password' => 'password']);
        $this->actingAs($url->author)
            ->get(route('link.password.edit', $url))
            ->assertSuccessful();
    }

    #[PHPUnit\Test]
    public function password_edit_adminCanAccessAll()
    {
        $url = Url::factory()->create(['password' => 'password']);
        $this->actingAs($this->adminUser())
            ->get(route('link.password.edit', $url))
            ->assertSuccessful();
    }

    #[PHPUnit\Test]
    public function password_edit_otherUserCantAccess()
    {
        $url = Url::factory()->create(['password' => 'password']);
        $this->actingAs($this->basicUser())
            ->get(route('link.password.edit', $url))
            ->assertForbidden();
    }

    /**
     * @see App\Http\Controllers\LinkPasswordController
     */
    public function testAddPasswordToLink()
    {
        $url = Url::factory()->create();
        $response = $this->actingAs($url->author)
            ->from(route('link.password.create', $url))
            ->post(route('link.password.store', $url), [
                'password' => 'password',
                'password_confirmation' => 'password',
            ]);

        $response
            ->assertRedirectToRoute('link.edit', $url)
            ->assertSessionHas('flash_success');
        $this->assertNotNull($url->fresh()->password);
    }

    /**
     * @see App\Http\Controllers\LinkPasswordController
     */
    public function testUpdatePasswordFromLink()
    {
        $url = Url::factory()->create(['password' => 'password']);
        $response = $this->actingAs($url->author)
            ->from(route('link.password.edit', $url))
            ->post(route('link.password.update', $url), [
                'password' => 'new-password',
                'password_confirmation' => 'new-password',
            ]);

        $response
            ->assertRedirectToRoute('link.edit', $url)
            ->assertSessionHas('flash_success');
        $this->assertTrue(Hash::check('new-password', $url->fresh()->password));
    }

    /**
     * @see App\Http\Controllers\LinkPasswordController
     */
    public function testRemovePasswordFromLink()
    {
        $url = Url::factory()->create(['password' => 'password']);
        $response = $this->actingAs($url->author)
            ->from(route('link.edit', $url))
            ->get(route('link.password.delete', $url));

        $response
            ->assertRedirectToRoute('link.edit', $url)
            ->assertSessionHas('flash_success');
        $this->assertNull($url->fresh()->password);
    }
}
