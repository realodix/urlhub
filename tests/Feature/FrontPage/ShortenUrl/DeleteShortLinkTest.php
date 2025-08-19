<?php

namespace Tests\Feature\FrontPage\ShortenUrl;

use App\Models\Url;
use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

#[\PHPUnit\Framework\Attributes\Group('front-page')]
class DeleteShortLinkTest extends TestCase
{
    const ROUTE_DEL_PARAM = ['redirect_to' => 'home'];

    #[PHPUnit\Test]
    public function delete_OwnedLink_ByUser_WillBeOk(): void
    {
        $url = Url::factory()->create();

        $response = $this->actingAs($url->author)
            ->from(route('link_detail', $url->keyword))
            ->delete(route('link.delete', $url->keyword), self::ROUTE_DEL_PARAM);

        $response->assertRedirectToRoute('home');
        $this->assertCount(0, Url::all());
    }

    /**
     * Test that guest users cannot delete any short URLs, regardless of who created
     * it, including themself.
     */
    #[PHPUnit\Test]
    public function delete_OwnedLink_ByGuest_WillBeForbidden(): void
    {
        $url = Url::factory()->guest()->create();
        $response = $this->from(route('link_detail', $url->keyword))
            ->delete(route('link.delete', $url->keyword), self::ROUTE_DEL_PARAM);
        $response->assertRedirectToRoute('login');

        $url = Url::factory()->create(['user_id' => $this->adminUser()->id]);
        $response = $this->from(route('link_detail', $url->keyword))
            ->delete(route('link.delete', $url->keyword), self::ROUTE_DEL_PARAM);
        $response->assertRedirectToRoute('login');

        $url = Url::factory()->create();
        $response = $this->from(route('link_detail', $url->keyword))
            ->delete(route('link.delete', $url->keyword), self::ROUTE_DEL_PARAM);
        $response->assertRedirectToRoute('login');

        $this->assertCount(3, Url::all());
    }

    /**
     * Test that an admin can delete short URLs created by other users.
     * The action should be allowed and the URL should be deleted,
     * resulting in a redirect to the home route.
     */
    #[PHPUnit\Test]
    public function delete_LinkOfOtherUser_ByAdmin_WillBeOk(): void
    {
        $url = Url::factory()->create();
        $response = $this->actingAs($this->adminUser())
            ->from(route('link_detail', $url->keyword))
            ->delete(route('link.delete', $url->keyword), self::ROUTE_DEL_PARAM);

        $response->assertRedirectToRoute('home');
        $this->assertCount(0, Url::all());
    }

    /**
     * When an admin tries to delete a short URL created by a guest user,
     * the action should be allowed and the URL should be deleted.
     */
    #[PHPUnit\Test]
    public function delete_LinkOfGuest_ByAdmin_WillBeOk(): void
    {
        $url = Url::factory()->guest()->create();
        $response = $this->actingAs($this->adminUser())
            ->from(route('link_detail', $url->keyword))
            ->delete(route('link.delete', $url->keyword), self::ROUTE_DEL_PARAM);

        $response->assertRedirectToRoute('home');
        $this->assertCount(0, Url::all());
    }

    /**
     * When a normal user tries to delete a short URL created by another user,
     * the action should be forbidden and the URL should not be deleted.
     */
    #[PHPUnit\Test]
    public function delete_LinkOfOtherUser_ByUser_WillBeForbidden(): void
    {
        $url = Url::factory()->create();
        $response = $this->actingAs($this->basicUser())
            ->from(route('link_detail', $url->keyword))
            ->delete(route('link.delete', $url->keyword), self::ROUTE_DEL_PARAM);

        $response->assertForbidden();
        $this->assertCount(1, Url::all());
    }

    /**
     * When a normal user tries to delete a short URL created by a guest user,
     * the action should be forbidden and the URL should not be deleted.
     */
    #[PHPUnit\Test]
    public function delete_LinkOfGuest_ByUser_WillBeForbidden(): void
    {
        $url = Url::factory()->guest()->create();
        $response = $this->actingAs($this->basicUser())
            ->from(route('link_detail', $url->keyword))
            ->delete(route('link.delete', $url->keyword), self::ROUTE_DEL_PARAM);

        $response->assertForbidden();
        $this->assertCount(1, Url::all());
    }
}
