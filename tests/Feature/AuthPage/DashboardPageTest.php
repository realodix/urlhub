<?php

namespace Tests\Feature\AuthPage;

use App\Models\Url;
use PHPUnit\Framework\Attributes\{Group, Test};
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
            ->get(route('dashboard.su_edit', $url->keyword));

        $response->assertOk();
    }

    #[Test]
    #[Group('f-dashboard')]
    public function dCanUpdateUrl(): void
    {
        $url = Url::factory()->create();

        $newLongUrl = 'https://phpunit.readthedocs.io/en/9.1';

        $response = $this->actingAs($url->author)
            ->from(route('dashboard.su_edit', $url->keyword))
            ->post(route('dashboard.su_edit.post', $url->keyword), [
                'title'    => $url->title,
                'long_url' => $newLongUrl,
            ]);

        $response
            ->assertRedirectToRoute('dashboard')
            ->assertSessionHas('flash_success');

        $this->assertSame($newLongUrl, $url->fresh()->destination);
    }
}
