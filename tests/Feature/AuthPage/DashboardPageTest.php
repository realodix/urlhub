<?php

namespace Tests\Feature\AuthPage;

use App\Models\Url;
use Tests\TestCase;

class DashboardPageTest extends TestCase
{
    /**
     * @test
     * @group f-dashboard
     */
    public function dCanAccessPage(): void
    {
        $response = $this->actingAs($this->normalUser())
            ->get(route('dashboard'));

        $response->assertOk();
    }

    /**
     * @test
     * @group f-dashboard
     */
    public function dCanDelete(): void
    {
        $url = Url::factory()->create();

        $response = $this->actingAs($url->author)
            ->from(route('dashboard'))
            ->get($this->secureRoute('dashboard.su_delete', $url->id));

        $response
            ->assertRedirectToRoute('dashboard')
            ->assertSessionHas('flash_success');

        $this->assertCount(0, Url::all());
    }

    /**
     * @test
     * @group f-dashboard
     */
    public function dCanDuplicate(): void
    {
        $url = Url::factory()->create();

        $response = $this->actingAs($url->author)
            ->from(route('dashboard'))
            ->get(route('dashboard.su_duplicate', $url->keyword));

        $response
            ->assertRedirectToRoute('dashboard')
            ->assertSessionHas('flash_success');

        $this->assertCount(2, Url::all());
    }

    /**
     * @test
     * @group f-dashboard
     */
    public function dAuthorizedUserCanAccessEditUrlPage(): void
    {
        $url = Url::factory()->create();

        $response = $this->actingAs($url->author)
            ->get(route('dashboard.su_edit', $url->keyword));

        $response->assertOk();
    }

    /**
     * @test
     * @group f-dashboard
     */
    public function dCanUpdateUrl(): void
    {
        $url = Url::factory()->create();

        $newLongUrl = 'https://phpunit.readthedocs.io/en/9.1';

        $response = $this->actingAs($url->author)
            ->from(route('dashboard.su_edit', $url->keyword))
            ->post($this->secureRoute('dashboard.su_edit.post', $url->id), [
                'title'    => $url->title,
                'long_url' => $newLongUrl,
            ]);

        $response
            ->assertRedirectToRoute('dashboard')
            ->assertSessionHas('flash_success');

        $this->assertSame($newLongUrl, $url->fresh()->destination);
    }
}
