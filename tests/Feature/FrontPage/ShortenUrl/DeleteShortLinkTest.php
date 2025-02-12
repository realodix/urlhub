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
            ->from(route('link_detail', $url->keyword))
            ->get(route('link_detail.delete', $url->keyword));

        $response->assertRedirectToRoute('home');
        $this->assertCount(0, Url::all());
    }

    /**
     * Test that an admin can delete short URLs created by other users.
     * The action should be allowed and the URL should be deleted,
     * resulting in a redirect to the home route.
     */
    public function testAdminCanDeleteUrLsCreatedByOtherUsers(): void
    {
        $url = Url::factory()->create();
        $response = $this->actingAs($this->adminUser())
            ->from(route('link_detail', $url->keyword))
            ->get(route('link_detail.delete', $url->keyword));

        $response->assertRedirectToRoute('home');
        $this->assertCount(0, Url::all());
    }

    /**
     * When an admin tries to delete a short URL created by a guest user,
     * the action should be allowed and the URL should be deleted.
     */
    public function testAdminCanDeleteUrlsCreatedByGuest(): void
    {
        $url = Url::factory()->guest()->create();
        $response = $this->actingAs($this->adminUser())
            ->from(route('link_detail', $url->keyword))
            ->get(route('link_detail.delete', $url->keyword));

        $response->assertRedirectToRoute('home');
        $this->assertCount(0, Url::all());
    }

    /**
     * When a normal user tries to delete a short URL created by another user,
     * the action should be forbidden and the URL should not be deleted.
     */
    public function testUserCannotDeleteUrlsCreatedByOtherUsers(): void
    {
        $url = Url::factory()->create();
        $response = $this->actingAs($this->basicUser())
            ->from(route('link_detail', $url->keyword))
            ->get(route('link_detail.delete', $url->keyword));

        $response->assertForbidden();
        $this->assertCount(1, Url::all());
    }

    /**
     * When a normal user tries to delete a short URL created by a guest user,
     * the action should be forbidden and the URL should not be deleted.
     */
    public function testUserCannotDeleteUrlsCreatedByGuest(): void
    {
        $url = Url::factory()->guest()->create();
        $response = $this->actingAs($this->basicUser())
            ->from(route('link_detail', $url->keyword))
            ->get(route('link_detail.delete', $url->keyword));

        $response->assertForbidden();
        $this->assertCount(1, Url::all());
    }

    /**
     * Test that guest users cannot delete any short URLs, regardless of who created
     * it, including themself.
     */
    public function testGuestCannotDeleteContent(): void
    {
        $url = Url::factory()->guest()->create();
        $response = $this->from(route('link_detail', $url->keyword))
            ->get(route('link_detail.delete', $url->keyword));
        $response->assertForbidden();

        $url = Url::factory()->create(['user_id' => $this->adminUser()->id]);
        $response = $this->from(route('link_detail', $url->keyword))
            ->get(route('link_detail.delete', $url->keyword));
        $response->assertForbidden();

        $url = Url::factory()->create();
        $response = $this->from(route('link_detail', $url->keyword))
            ->get(route('link_detail.delete', $url->keyword));
        $response->assertForbidden();

        $this->assertCount(3, Url::all());
    }
}
