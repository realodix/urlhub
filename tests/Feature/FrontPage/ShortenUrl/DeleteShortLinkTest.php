<?php

namespace Tests\Feature\FrontPage\ShortenUrl;

use App\Models\Url;
use Tests\TestCase;

class DeleteShortLinkTest extends TestCase
{
    /** @test */
    public function userCanDelete(): void
    {
        $url = Url::factory()->create();

        $response = $this->actingAs($url->author)
            ->from(route('su_detail', $url->keyword))
            ->get($this->secureRoute('su_delete', $url->id));

        $response->assertRedirectToRoute('home');
        $this->assertCount(0, Url::all());
    }

    /** @test */
    public function adminCanDeleteUrlsCreatedByOtherUsers(): void
    {
        $url = Url::factory()->create();
        $response = $this->actingAs($this->adminUser())
            ->from(route('su_detail', $url->keyword))
            ->get($this->secureRoute('su_delete', $url->id));

        $response->assertRedirectToRoute('home');
        $this->assertCount(0, Url::all());
    }

    /** @test */
    public function adminCanDeleteUrlsCreatedByGuest(): void
    {
        $url = Url::factory()->create(['user_id' => Url::GUEST_ID]);
        $response = $this->actingAs($this->adminUser())
            ->from(route('su_detail', $url->keyword))
            ->get($this->secureRoute('su_delete', $url->id));

        $response->assertRedirectToRoute('home');
        $this->assertCount(0, Url::all());
    }

    /** @test */
    public function userCannotDeleteUrlsCreatedByOtherUsers(): void
    {
        $url = Url::factory()->create();
        $response = $this->actingAs($this->normalUser())
            ->from(route('su_detail', $url->keyword))
            ->get($this->secureRoute('su_delete', $url->id));

        $response->assertForbidden();
        $this->assertCount(1, Url::all());
    }

    /** @test */
    public function userCannotDeleteUrlsCreatedByGuest(): void
    {
        $url = Url::factory()->create(['user_id' => Url::GUEST_ID]);
        $response = $this->actingAs($this->normalUser())
            ->from(route('su_detail', $url->keyword))
            ->get($this->secureRoute('su_delete', $url->id));

        $response->assertForbidden();
        $this->assertCount(1, Url::all());
    }

    /** @test */
    public function guestCannotDelete(): void
    {
        $url = Url::factory()->create(['user_id' => Url::GUEST_ID]);
        $response = $this->from(route('su_detail', $url->keyword))
            ->get($this->secureRoute('su_delete', $url->id));
        $response->assertForbidden();

        $url = Url::factory()->create(['user_id' => $this->adminUser()->id]);
        $response = $this->from(route('su_detail', $url->keyword))
            ->get($this->secureRoute('su_delete', $url->id));
        $response->assertForbidden();

        $url = Url::factory()->create();
        $response = $this->from(route('su_detail', $url->keyword))
            ->get($this->secureRoute('su_delete', $url->id));
        $response->assertForbidden();

        $this->assertCount(3, Url::all());
    }
}
