<?php

namespace Tests\Unit\Controllers;

use App\Models\{Url, Visit};
use Tests\TestCase;

class UrlRedirectControllerTest extends TestCase
{
    /**
     * @test
     *
     * @group u-controller
     */
    public function urlRedirection()
    {
        $url = Url::factory()->create();

        $response = $this->get(route('home').'/'.$url->keyword);
        $response->assertRedirect($url->long_url);
        $response->assertStatus((int) config('urlhub.redirect_status_code'));

        $this->assertCount(1, Visit::all());
    }
}
