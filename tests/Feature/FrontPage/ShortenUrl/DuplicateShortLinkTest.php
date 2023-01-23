<?php

namespace Tests\Feature\FrontPage\ShortenUrl;

use App\Models\Url;
use Tests\TestCase;

class DuplicateShortLinkTest extends TestCase
{
    /** @test */
    public function duplicate()
    {
        $url = Url::factory()->create([
            'user_id' => $this->adminUser()->id,
        ]);

        $this->post(route('su_create'), [
            'long_url' => $url->destination,
        ]);

        $this->from(route('su_detail', $url->keyword))
            ->get(route('su_duplicate', $url->keyword));

        $this->assertCount(2, Url::all());
    }

    /** @test */
    public function duplicateUrlCreatedByGuest()
    {
        $url = Url::factory()->create([
            'user_id' => Url::GUEST_ID,
        ]);

        $this->actingAs($this->adminUser())
            ->post(route('su_create'), [
                'long_url' => $url->destination,
            ]);

        $this->from(route('su_detail', $url->keyword))
            ->get(route('su_duplicate', $url->keyword));

        $this->assertCount(3, Url::all());
    }

    /** @test */
    public function guestCannotDuplicateUrl()
    {
        $url = Url::factory()->create([
            'user_id' => Url::GUEST_ID,
        ]);

        $this->post(route('su_create'), [
            'long_url' => $url->destination,
        ]);

        $response = $this->from(route('su_detail', $url->keyword))
            ->get(route('su_duplicate', $url->keyword));

        $response->assertRedirectToRoute('login');
        $this->assertCount(1, Url::all());
    }
}
