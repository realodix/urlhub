<?php

namespace Tests\Feature\FrontPage\ShortenUrl;

use App\Models\Url;
use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

#[\PHPUnit\Framework\Attributes\Group('front-page')]
class DetailPageTest extends TestCase
{
    #[PHPUnit\Test]
    public function showDetail(): void
    {
        $url = Url::factory()->create();

        $response = $this->get(route('link_detail', $url->keyword));

        $response->assertStatus(200);
    }
}
