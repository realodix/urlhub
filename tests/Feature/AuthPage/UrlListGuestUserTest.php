<?php

namespace Tests\Feature\AuthPage;

use App\Models\Url;
use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

#[PHPUnit\Group('auth-page')]
#[PHPUnit\Group('link-page')]
class UrlListGuestUserTest extends TestCase
{
    private $url;

    protected function setUp(): void
    {
        parent::setUp();

        // This must be created, to ensure `$url->author->name` does not raise
        // an error.
        $this->url = Url::factory()->create(['user_id' => Url::GUEST_ID]);
    }

    #[PHPUnit\Test]
    public function adminCanAccessPage(): void
    {
        $response = $this->actingAs($this->adminUser())
            ->get(route('dashboard.allurl.u-guest'));
        $response->assertOk();
    }

    /**
     * Non admin users can't access guest links table page.
     */
    #[PHPUnit\Test]
    public function basicUsersCantAccessPage(): void
    {
        $response = $this->actingAs($this->basicUser())
            ->get(route('dashboard.allurl.u-guest'));
        $response->assertForbidden();
    }

    #[PHPUnit\Test]
    public function adminUserCanAccessEditUrlPage(): void
    {
        $response = $this->actingAs($this->adminUser())
            ->get(route('dboard.url.edit.show', $this->url->keyword));

        $response->assertOk();
    }
}
