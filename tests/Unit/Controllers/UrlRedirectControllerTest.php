<?php

namespace Tests\Unit\Controllers;

use App\Models\Url;
use App\Models\Visit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UrlRedirectControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @group u-controller
     */
    public function url_redirection()
    {
        $url = Url::factory()->create();

        $response = $this->get(route('home').'/'.$url->keyword);
        $response->assertRedirect($url->long_url);
        $response->assertStatus(uHub('redirect_status_code'));

        $this->assertCount(1, Visit::all());
    }
}
