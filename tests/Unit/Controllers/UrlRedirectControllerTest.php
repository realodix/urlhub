<?php

namespace Tests\Unit\Controllers;

use App\Models\Url;
use App\Models\Visit;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[\PHPUnit\Framework\Attributes\Group('controller')]
class UrlRedirectControllerTest extends TestCase
{
    #[Test]
    public function urlRedirection(): void
    {
        $url = Url::factory()->create();

        $response = $this->get(route('home').'/'.$url->keyword);
        $response->assertRedirect($url->destination)
            ->assertStatus((int) config('urlhub.redirect_status_code'));

        $this->assertCount(1, Visit::all());
    }
}
