<?php

namespace Tests\Unit\Controllers;

use App\Models\Url;
use App\Models\Visit;
use Tests\TestCase;

#[\PHPUnit\Framework\Attributes\Group('controller')]
class UrlRedirectControllerTest extends TestCase
{
    public function testUrlRedirection(): void
    {
        $url = Url::factory()->create();

        $response = $this->get(route('home').'/'.$url->keyword);
        $response->assertRedirect($url->destination)
            ->assertStatus(config('urlhub.redirect_status_code'));

        $this->assertCount(1, Visit::all());
    }
}
