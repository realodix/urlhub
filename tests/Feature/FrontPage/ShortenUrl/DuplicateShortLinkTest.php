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
}
