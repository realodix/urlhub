<?php

namespace Tests\Unit\Middleware;

use App\Url;
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
        config()->set('urlhub.hash_size_2', 0);

        $response = $this->post(route('createshortlink'), [
            'long_url' => 'https://laravel.com',
        ]);

        $response
            ->assertRedirect(route('home'))
            ->assertSessionHas('flash_error');
    }

    /**
     * With authenticated user.
     *
     * @test
     * @covers ::handle
     */
    public function long_url_already_exist_1()
    {
        $user = $this->admin();
        $url = factory(Url::class)->create([
            'user_id'  => $user->id,
        ]);

        $this->loginAsAdmin();

        $response = $this->post(route('createshortlink'), [
            'long_url' => $url->long_url,
        ]);
        $response->assertSessionHas('msgLinkAlreadyExists');
    }

    /**
     * @test
     * @covers ::handle
     */
    public function long_url_already_exist_2()
    {
        $url = factory(Url::class)->create([
            'user_id'  => null,
        ]);

        $response = $this->post(route('createshortlink'), [
            'long_url' => $url->long_url,
        ]);
        $response->assertSessionHas('msgLinkAlreadyExists');
    }
}
