<?php

namespace Tests\Feature\FrontPage\ShortenUrl;

use App\Models\Url;
use Tests\TestCase;
use Vinkla\Hashids\Facades\Hashids;

class DeleteShortLinkTest extends TestCase
{
    protected function hashIdRoute($routeName, $url_id)
    {
        $hashids = Hashids::connection(Url::class);

        return route($routeName, $hashids->encode($url_id));
    }

    /** @test */
    public function userCanDelete()
    {
        $url = Url::factory()->create();

        $response = $this->actingAs($url->author)
            ->from(route('su_detail', $url->keyword))
            ->get($this->hashIdRoute('su_delete', $url->id));

        $response->assertRedirectToRoute('home');
        $this->assertCount(0, Url::all());
    }

    /** @test */
    public function adminCanDeleteUrlsCreatedByOtherUsers()
    {
        $url = Url::factory()->create();
        $response = $this->actingAs($this->adminUser())
            ->from(route('su_detail', $url->keyword))
            ->get($this->hashIdRoute('su_delete', $url->id));

        $response->assertRedirectToRoute('home');
        $this->assertCount(0, Url::all());
    }

    /** @test */
    public function adminCanDeleteUrlsCreatedByGuest()
    {
        $url = Url::factory()->create(['user_id' => Url::GUEST_ID]);
        $response = $this->actingAs($this->adminUser())
            ->from(route('su_detail', $url->keyword))
            ->get($this->hashIdRoute('su_delete', $url->id));

        $response->assertRedirectToRoute('home');
        $this->assertCount(0, Url::all());
    }

    /** @test */
    public function userCannotDeleteUrlsCreatedByOtherUsers()
    {
        $url = Url::factory()->create();
        $response = $this->actingAs($this->normalUser())
            ->from(route('su_detail', $url->keyword))
            ->get($this->hashIdRoute('su_delete', $url->id));

        $response->assertForbidden();
        $this->assertCount(1, Url::all());
    }

    /** @test */
    public function userCannotDeleteUrlsCreatedByGuest()
    {
        $url = Url::factory()->create(['user_id' => Url::GUEST_ID]);
        $response = $this->actingAs($this->normalUser())
            ->from(route('su_detail', $url->keyword))
            ->get($this->hashIdRoute('su_delete', $url->id));

        $response->assertForbidden();
        $this->assertCount(1, Url::all());
    }

    /** @test */
    public function guestCannotDelete()
    {
        $url = Url::factory()->create(['user_id' => Url::GUEST_ID]);
        $response = $this->from(route('su_detail', $url->keyword))
            ->get($this->hashIdRoute('su_delete', $url->id));
        $response->assertForbidden();

        $url = Url::factory()->create(['user_id' => $this->adminUser()->id]);
        $response = $this->from(route('su_detail', $url->keyword))
            ->get($this->hashIdRoute('su_delete', $url->id));
        $response->assertForbidden();

        $url = Url::factory()->create();
        $response = $this->from(route('su_detail', $url->keyword))
            ->get($this->hashIdRoute('su_delete', $url->id));
        $response->assertForbidden();

        $this->assertCount(3, Url::all());
    }
}
