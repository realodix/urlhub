<?php

namespace Tests\Unit\Middleware;

use Tests\TestCase;

class UrlHubLinkCheckerTest extends TestCase
{
    /**
     * Shorten the url when the random string generator capacity is full.
     * UrlHub must prevent URL shortening.
     *
     * @test
     */
    public function keyRemaining_zero()
    {
        config()->set('urlhub.hash_length', 0);

        $response = $this->post(route('createshortlink'), [
            'long_url' => 'https://laravel.com',
        ]);

        $response
            ->assertRedirect(route('home'))
            ->assertSessionHas('flash_error');
    }
}
