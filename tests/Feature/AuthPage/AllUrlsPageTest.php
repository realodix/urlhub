<?php

namespace Tests\Feature\AuthPage;

use App\Models\Url;
use PHPUnit\Framework\Attributes\{Group, Test};
use Tests\TestCase;

class AllUrlsPageTest extends TestCase
{
    #[Test]
    #[Group('f-allurl')]
    public function auAdminCanAccessThisPage(): void
    {
        $response = $this->actingAs($this->adminUser())
            ->get(route('dashboard.allurl'));

        $response->assertOk();
    }

    #[Test]
    #[Group('f-allurl')]
    public function auNormalUserCantAccessThisPage(): void
    {
        $response = $this->actingAs($this->normalUser())
            ->get(route('dashboard.allurl'));

        $response->assertForbidden();
    }

    #[Test]
    #[Group('f-allurl')]
    public function auAdminCanDelete(): void
    {
        $url = Url::factory()->create();

        $response = $this->actingAs($this->adminUser())
            ->from(route('dashboard.allurl'))
            ->get(route('dashboard.allurl.su_delete', $url->keyword));

        $response->assertRedirectToRoute('dashboard.allurl')
            ->assertSessionHas('flash_success');

        $this->assertCount(0, Url::all());
    }

    #[Test]
    #[Group('f-allurl')]
    public function auNormalUserCantDelete(): void
    {
        $url = Url::factory()->create();

        $response = $this->actingAs($this->normalUser())
            ->from(route('dashboard.allurl'))
            ->get(route('dashboard.allurl.su_delete', $url->keyword));

        $response->assertForbidden();
        $this->assertCount(1, Url::all());
    }
}
