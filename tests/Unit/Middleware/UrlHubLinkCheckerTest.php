<?php

namespace Tests\Unit\Middleware;

use Tests\TestCase;

class UrlHubLinkCheckerTest extends TestCase
{
    /**
     * @test
     */
    public function keywordRemaining_zero()
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
