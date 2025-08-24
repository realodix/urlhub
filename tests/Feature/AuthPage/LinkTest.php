<?php

namespace Tests\Feature\AuthPage;

use App\Models\Url;
use App\Rules\LinkRules;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\TestResponse;
use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\Support\Helper;
use Tests\TestCase;

#[PHPUnit\Group('auth-page')]
#[PHPUnit\Group('link-page')]
class LinkTest extends TestCase
{
    private function postLinkUpdateRequest(Url $url, array $data): TestResponse
    {
        return $this->post(
            route('link.update', $url->keyword),
            Helper::updateLinkData($url, $data),
        );
    }

    /**
     * Test that an authorized user can update a link.
     *
     * @see \App\Http\Controllers\LinkController::update()
     */
    #[PHPUnit\Test]
    public function updateLink(): void
    {
        $url = Url::factory()->create();
        $newLongUrl = 'https://phpunit.readthedocs.io/en/9.1';
        $response = $this->actingAs($url->author)
            ->from(route('link.edit', $url->keyword))
            ->postLinkUpdateRequest($url, ['long_url' => $newLongUrl]);

        $response
            ->assertRedirectToRoute('link.edit', $url->keyword)
            ->assertSessionHas('flash_success');
        $this->assertSame($newLongUrl, $url->fresh()->destination);
    }

    /**
     * @see \App\Http\Controllers\LinkController::update()
     */
    #[PHPUnit\Test]
    public function validate_update__title_length(): void
    {
        $url = Url::factory()->create();
        $response = $this->actingAs($url->author)
            ->from(route('link.edit', $url->keyword))
            ->postLinkUpdateRequest($url, [
                'title' => str_repeat('a', LinkRules::TITLE_MAX_LENGTH + 1),
            ]);

        $response
            ->assertRedirect(route('link.edit', $url->keyword))
            ->assertSessionHasErrors('title');
    }

    /**
     * @see \App\Http\Controllers\LinkController::update()
     */
    #[PHPUnit\Test]
    public function validate_update__long_url_is_url(): void
    {
        $url = Url::factory()->create();
        $response = $this->actingAs($url->author)
            ->from(route('link.edit', $url->keyword))
            ->postLinkUpdateRequest($url, [
                'long_url' => 'invalid-url',
                'dest_android' => 'invalid-url',
                'dest_ios' => 'invalid-url',
                'expired_url' => 'invalid-url',
            ]);

        $response
            ->assertRedirect(route('link.edit', $url->keyword))
            ->assertSessionHasErrors(['long_url', 'dest_android', 'dest_ios', 'expired_url']);
    }

    /**
     * @see \App\Http\Controllers\LinkController::update()
     */
    #[PHPUnit\Test]
    public function validate_update__long_url_max_length(): void
    {
        $veryLongUrl = 'https://laravel.com/'.str_repeat('a', LinkRules::MAX_LENGTH);

        $url = Url::factory()->create();
        $response = $this->actingAs($url->author)
            ->from(route('link.edit', $url->keyword))
            ->postLinkUpdateRequest($url, [
                'long_url' => $veryLongUrl,
                'dest_android' => $veryLongUrl,
                'dest_ios' => $veryLongUrl,
                'expired_url' => $veryLongUrl,
            ]);

        $response
            ->assertRedirect(route('link.edit', $url->keyword))
            ->assertSessionHasErrors(['long_url', 'dest_android', 'dest_ios', 'expired_url']);
    }

    /**
     * @see \App\Http\Controllers\LinkController::update()
     * @see \App\Rules\LinkRules::link()
     * @see \App\Rules\AllowedDomain
     */
    #[PHPUnit\Test]
    public function validate_update__long_url_not_blacklisted()
    {
        config(['urlhub.blacklist_domain' => ['t.co']]);
        $blacklistedDomain = 'https://t.co/about';
        $url = Url::factory()->create();

        $response = $this->actingAs($url->author)
            ->from(route('link.edit', $url->keyword))
            ->postLinkUpdateRequest($url, [
                'long_url' => $blacklistedDomain,
                'dest_android' => $blacklistedDomain,
                'dest_ios' => $blacklistedDomain,
                'expired_url' => $blacklistedDomain,
            ]);

        $response
            ->assertRedirect(route('link.edit', $url->keyword))
            ->assertSessionHasErrors(['long_url', 'dest_android', 'dest_ios', 'expired_url']);
    }

    #[PHPUnit\Test]
    public function validate_update__ExpiresAt_Valid()
    {
        $url = Url::factory()->create();
        $response = $this->actingAs($url->author)
            ->from(route('link.edit', $url->keyword))
            ->postLinkUpdateRequest($url, ['expires_at' => now()->addDay()->format('Y-m-d')]);

        $response
            ->assertRedirectToRoute('link.edit', $url->keyword)
            ->assertSessionHas('flash_success');
        $this->assertNotNull($url->fresh()->expires_at);

        $response = $this->actingAs($url->author)
            ->from(route('link.edit', $url->keyword))
            ->postLinkUpdateRequest($url, ['expires_at' => null]); // remove expires_at

        $response
            ->assertRedirectToRoute('link.edit', $url->keyword)
            ->assertSessionHas('flash_success');
        $this->assertNull($url->fresh()->expires_at);
    }

    #[PHPUnit\Test]
    public function validate_update__ExpiresAt_Invalid()
    {
        $url = Url::factory()->create();
        $response = $this->actingAs($url->author)
            ->from(route('link.edit', $url->keyword))
            ->postLinkUpdateRequest($url, ['expires_at' => now()->subMinute()]);

        $response
            ->assertRedirectToRoute('link.edit', $url->keyword)
            ->assertSessionHasErrors(['expires_at']);
    }

    #[PHPUnit\Test]
    public function validate_update__WithNullableValue()
    {
        $url = Url::factory()->create();
        $response = $this->actingAs($url->author)
            ->from(route('link.edit', $url->keyword))
            ->postLinkUpdateRequest($url, [
                'dest_android' => '',
                'dest_ios' => '',
                'expired_url' => '',
            ]);

        $response
            ->assertRedirectToRoute('link.edit', $url->keyword)
            ->assertSessionHas('flash_success');
        $this->assertNull($url->fresh()->dest_android);
        $this->assertNull($url->fresh()->dest_ios);
        $this->assertNull($url->fresh()->expired_url);
    }

    /**
     * @see \App\Http\Controllers\LinkPasswordController
     */
    #[PHPUnit\Test]
    public function validate_AddPasswordToLink()
    {
        $url = Url::factory()->create();
        $response = $this->actingAs($url->author)
            ->from(route('link.edit', $url))
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
     * @see \App\Http\Controllers\LinkPasswordController
     */
    #[PHPUnit\Test]
    public function validate_PasswordFromLink()
    {
        $url = Url::factory()->create(['password' => 'password']);
        $response = $this->actingAs($url->author)
            ->from(route('link.edit', $url))
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
     * @see \App\Http\Controllers\LinkPasswordController
     */
    #[PHPUnit\Test]
    public function validate_RemovePasswordFromLink()
    {
        $url = Url::factory()->create(['password' => 'password']);
        $response = $this->actingAs($url->author)
            ->from(route('link.edit', $url))
            ->delete(route('link.password.delete', $url));

        $response
            ->assertRedirectToRoute('link.edit', $url)
            ->assertSessionHas('flash_success');
        $this->assertNull($url->fresh()->password);
    }
}
