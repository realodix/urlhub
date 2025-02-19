<?php

namespace Tests\Feature\AuthPage;

use App\Http\Requests\StoreUrlRequest;
use App\Models\Url;
use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\Support\Helper;
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
     * @see App\Http\Controllers\UrlController::delete()
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
            ->assertRedirectToRoute('dashboard')
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
                Helper::updateLinkData($url, ['long_url' => 'invalid-url']),
            );

        $response
            ->assertRedirect(route('link.edit', $url->keyword))
            ->assertSessionHasErrors('long_url');
    }

    /**
     * @see App\Http\Controllers\UrlController::update()
     */
    public function test_update_validates_long_url_max_length(): void
    {
        $url = Url::factory()->create();
        $response = $this->actingAs($url->author)
            ->from(route('link.edit', $url->keyword))
            ->post(
                route('link.update', $url->keyword),
                Helper::updateLinkData($url, [
                    'long_url' => 'https://laravel.com/'.str_repeat('a', StoreUrlRequest::URL_LENGTH),
                ]),
            );

        $response
            ->assertRedirect(route('link.edit', $url->keyword))
            ->assertSessionHasErrors('long_url');
    }

    /**
     * @see App\Http\Controllers\UrlController::update()
     */
    public function test_update_validates_long_url_not_blacklisted()
    {
        config(['urlhub.domain_blacklist' => ['t.co']]);
        $url = Url::factory()->create();
        $response = $this->actingAs($url->author)
            ->from(route('link.edit', $url->keyword))
            ->post(
                route('link.update', $url->keyword),
                Helper::updateLinkData($url, ['long_url' => 'https://t.co/about']),
            );

        $response
            ->assertRedirect(route('link.edit', $url->keyword))
            ->assertSessionHasErrors('long_url');
    }
}
