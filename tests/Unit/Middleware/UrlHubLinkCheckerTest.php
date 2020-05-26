<?php

namespace Tests\Unit\Middleware;

use Tests\TestCase;

/**
 * @coversDefaultClass App\Http\Middleware\UrlHubLinkChecker
 */
class UrlHubLinkCheckerTest extends TestCase
{
    /**
     * @test
     * @covers ::handle
     */
    public function url_key_remaining_zero()
    {
        config()->set('urlhub.hash_size_1', 0);

        $response = $this->post(route('createshortlink'), [
            'long_url' => 'https://laravel.com',
        ]);

        $response
            ->assertRedirect(route('home'))
            ->assertSessionHas('flash_error');
    }
}
