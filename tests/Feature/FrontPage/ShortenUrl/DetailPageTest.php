<?php

namespace Tests\Feature\FrontPage\ShortenUrl;

use App\Models\Url;
use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

#[PHPUnit\Group('front-page')]
class DetailPageTest extends TestCase
{
    #[PHPUnit\Test]
    public function showDetail(): void
    {
        $url = Url::factory()->create();

        $response = $this->get(route('link_detail', $url->keyword));

        $response->assertStatus(200);
    }

    #[PHPUnit\Test]
    public function expired(): void
    {
        $url = Url::factory()->create(['expires_at' => now()->subDay()]);

        $response = $this->actingAs($url->author)
            ->get(route('link_detail', $url->keyword));
        $response->assertSee($url->destination);

        $response = $this->actingAs($this->adminUser())
            ->get(route('link_detail', $url->keyword));
        $response->assertSee($url->destination);

        $response = $this->actingAs($this->basicUser())
            ->get(route('link_detail', $url->keyword));
        $response->assertDontSee($url->destination);

        $response = $this->actingAsGuest()
            ->get(route('link_detail', $url->keyword));
        $response->assertDontSee($url->destination);
    }

    #[PHPUnit\Test]
    public function password_protected(): void
    {
        $url = Url::factory()->create(['password' => 'secret']);

        $response = $this->actingAs($url->author)
            ->get(route('link_detail', $url->keyword));
        $response->assertSee($url->destination);

        $response = $this->actingAs($this->adminUser())
            ->get(route('link_detail', $url->keyword));
        $response->assertSee($url->destination);

        $response = $this->actingAs($this->basicUser())
            ->get(route('link_detail', $url->keyword));
        $response->assertDontSee($url->destination);

        $response = $this->actingAsGuest()
            ->get(route('link_detail', $url->keyword));
        $response->assertDontSee($url->destination);
    }
}
