<?php

namespace Tests\Feature\AuthPage;

use App\Models\Url;
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
}
