<?php

namespace Tests\Feature\FrontPage\ShortenUrl;

use App\Models\Url;
use Tests\TestCase;

class DuplicateShortLinkTest extends TestCase
{
    /** @test */
    public function duplicate(): void
    {
        $url = Url::factory()->create();

        $this->post(route('su_create'), [
            'long_url' => $url->destination,
        ]);

        $this->from(route('su_detail', $url->keyword))
            ->get(route('su_duplicate', $url->keyword));

        $this->assertCount(2, Url::all());
    }

    /**
     * Users can duplicate short links created by guests.
     *
     * @test
     */
    public function duplicateUrlCreatedByGuest(): void
    {
        $url = Url::factory()->create(['user_id' => Url::GUEST_ID]);

        $this->actingAs($this->normalUser())
            ->post(route('su_create'), [
                'long_url' => $url->destination,
            ]);

        $this->from(route('su_detail', $url->keyword))
            ->get(route('su_duplicate', $url->keyword));

        $this->assertCount(3, Url::all());
    }

    /**
     * Guest cannot duplicate short links
     *
     * @test
     */
    public function guestCannotDuplicateUrl(): void
    {
        $url = Url::factory()->create(['user_id' => Url::GUEST_ID]);

        $this->post(route('su_create'), [
            'long_url' => $url->destination,
        ]);

        $response = $this->from(route('su_detail', $url->keyword))
            ->get(route('su_duplicate', $url->keyword));

        $response->assertRedirectToRoute('login');
        $this->assertCount(1, Url::all());
    }
}
