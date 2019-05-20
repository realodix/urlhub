<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UrlTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function create_short_url()
    {
        $long_url = 'https://laravel.com';

        $response = $this->post(route('createshortlink'), [
            'long_url' => $long_url,
        ]);

        // $this->assertEquals($long_url, $url->long_url);
        $this->assertDatabaseHas('urls', [
            'long_url' => $long_url,
        ]);
    }
}
