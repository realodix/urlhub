<?php

namespace Tests\Feature\AuthPage;

use App\Http\Controllers\Dashboard\DashboardController;
use App\Models\Url;
use Illuminate\Http\Request;
use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

#[PHPUnit\Group('auth-page')]
#[PHPUnit\Group('link-page')]
class DashboardPageTest extends TestCase
{
    #[PHPUnit\Test]
    public function dCanAccessPage(): void
    {
        $response = $this->actingAs($this->basicUser())
            ->get(route('dashboard'));

        $response->assertOk();
    }

    #[PHPUnit\Test]
    public function dCanDelete(): void
    {
        $url = Url::factory()->create();

        $response = $this->actingAs($url->author)
            ->from(route('dashboard'))
            ->get(route('dboard.url.delete', $url->keyword));

        $response
            ->assertRedirectToRoute('dashboard')
            ->assertSessionHas('flash_success');

        $this->assertCount(0, Url::all());
    }

    #[PHPUnit\Test]
    public function dAuthorizedUserCanAccessEditUrlPage(): void
    {
        $url = Url::factory()->create();

        $response = $this->actingAs($url->author)
            ->get(route('dboard.url.edit.show', $url->keyword));

        $response->assertOk();
    }

    #[PHPUnit\Test]
    public function dCanUpdateUrl(): void
    {
        $url = Url::factory()->create();
        $newLongUrl = 'https://phpunit.readthedocs.io/en/9.1';
        $response = $this->actingAs($url->author)
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
     * A normal user can't change the password of another user.
     *
     * This test simulates a normal user trying to change the password of another
     * user, verifies that the operation is forbidden by checking for a forbidden
     * response, and confirms that the password is unchanged in the database.
     */
    #[PHPUnit\Test]
    public function normalUserCantUpdateOtherUsersUrl(): void
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

    public function test_update_validates_title_length(): void
    {
        $url = Url::factory()->create();
        $response = $this->actingAs($url->author)
            ->from(route('dboard.url.edit.show', $url->keyword))
            ->post(route('dboard.url.edit.store', $url->keyword), [
                'title'    => str_repeat('a', Url::TITLE_LENGTH + 1),
                'long_url' => 'https://laravel.com/',
            ]);

        $response
            ->assertRedirect(route('dboard.url.edit.show', $url->keyword))
            ->assertSessionHasErrors('title');
    }

    public function test_update_validates_long_url_is_url(): void
    {
        $url = Url::factory()->create();
        $response = $this->actingAs($url->author)
            ->from(route('dboard.url.edit.show', $url->keyword))
            ->post(route('dboard.url.edit.store', $url->keyword), [
                'title'    => 'Laravel',
                'long_url' => 'invalid-url',
            ]);

        $response
            ->assertRedirect(route('dboard.url.edit.show', $url->keyword))
            ->assertSessionHasErrors('long_url');
    }

    public function test_update_validates_long_url_max_length(): void
    {
        $url = Url::factory()->create();
        $response = $this->actingAs($url->author)
            ->from(route('dboard.url.edit.show', $url->keyword))
            ->post(route('dboard.url.edit.store', $url->keyword), [
                'title'    => 'Laravel',
                'long_url' => 'https://laravel.com/' . str_repeat('a', 65536),
            ]);

        $response
            ->assertRedirect(route('dboard.url.edit.show', $url->keyword))
            ->assertSessionHasErrors('long_url');
    }

    public function test_update_validates_long_url_not_blacklisted()
    {
        config(['urlhub.domain_blacklist' => ['t.co']]);
        $url = Url::factory()->create();
        $response = $this->actingAs($url->author)
            ->from(route('dboard.url.edit.show', $url->keyword))
            ->post(route('dboard.url.edit.store', $url->keyword), [
                'title'    => 'Laravel',
                'long_url' => 'https://t.co/about',
            ]);

        $response
            ->assertRedirect(route('dboard.url.edit.show', $url->keyword))
            ->assertSessionHasErrors('long_url');
    }
}
