<?php

namespace Tests\Feature\AuthPage;

use App\Models\Url;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DashboardPageTest extends TestCase
{
    #[Test]
    #[Group('f-dashboard')]
    public function dCanAccessPage(): void
    {
        $response = $this->actingAs($this->normalUser())
            ->get(route('dashboard'));

        $response->assertOk();
    }

    #[Test]
    #[Group('f-dashboard')]
    public function dCanDelete(): void
    {
        $url = Url::factory()->create();

        $response = $this->actingAs($url->author)
            ->from(route('dashboard'))
            ->get(route('dashboard.su_delete', $url->keyword));

        $response
            ->assertRedirectToRoute('dashboard')
            ->assertSessionHas('flash_success');

        $this->assertCount(0, Url::all());
    }

    #[Test]
    #[Group('f-dashboard')]
    public function dAuthorizedUserCanAccessEditUrlPage(): void
    {
        $url = Url::factory()->create();

        $response = $this->actingAs($url->author)
            ->get(route('dboard.url.edit.show', $url->keyword));

        $response->assertOk();
    }

    #[Test]
    #[Group('f-dashboard')]
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
