<?php

namespace Tests\Feature\FrontPage\ShortenUrl;

use App\Models\Url;
use Tests\TestCase;

#[\PHPUnit\Framework\Attributes\Group('front-page')]
class DeleteShortLinkTest extends TestCase
{
    public function testUserCanDeleteContent(): void
    {
        $url = Url::factory()->create();

        $response = $this->actingAs($url->author)
            ->from(route('su_detail', $url->keyword))
            ->get(route('su_delete', $url->keyword));

        $response->assertRedirectToRoute('home');
        $this->assertCount(0, Url::all());
    }

    public function testAdminCanDeleteUrLsCreatedByOtherUsers(): void
    {
        $url = Url::factory()->create();
        $response = $this->actingAs($this->adminUser())
            ->from(route('su_detail', $url->keyword))
            ->get(route('su_delete', $url->keyword));

        $response->assertRedirectToRoute('home');
        $this->assertCount(0, Url::all());
    }

    public function testAdminCanDeleteUrlsCreatedByGuest(): void
    {
        $url = Url::factory()->create(['user_id' => Url::GUEST_ID]);
        $response = $this->actingAs($this->adminUser())
            ->from(route('su_detail', $url->keyword))
            ->get(route('su_delete', $url->keyword));

        $response->assertRedirectToRoute('home');
        $this->assertCount(0, Url::all());
    }

    public function testUserCannotDeleteUrlsCreatedByOtherUsers(): void
    {
        $url = Url::factory()->create();
        $response = $this->actingAs($this->normalUser())
            ->from(route('su_detail', $url->keyword))
            ->get(route('su_delete', $url->keyword));

        $response->assertForbidden();
        $this->assertCount(1, Url::all());
    }

    public function testUserCannotDeleteUrlsCreatedByGuest(): void
    {
        $url = Url::factory()->create(['user_id' => Url::GUEST_ID]);
        $response = $this->actingAs($this->normalUser())
            ->from(route('su_detail', $url->keyword))
            ->get(route('su_delete', $url->keyword));

        $response->assertForbidden();
        $this->assertCount(1, Url::all());
    }

    public function testGuestCannotDeleteContent(): void
    {
        $url = Url::factory()->create(['user_id' => Url::GUEST_ID]);
        $response = $this->from(route('su_detail', $url->keyword))
            ->get(route('su_delete', $url->keyword));
        $response->assertForbidden();

        $url = Url::factory()->create(['user_id' => $this->adminUser()->id]);
        $response = $this->from(route('su_detail', $url->keyword))
            ->get(route('su_delete', $url->keyword));
        $response->assertForbidden();

        $url = Url::factory()->create();
        $response = $this->from(route('su_detail', $url->keyword))
            ->get(route('su_delete', $url->keyword));
        $response->assertForbidden();

        $this->assertCount(3, Url::all());
    }
}
