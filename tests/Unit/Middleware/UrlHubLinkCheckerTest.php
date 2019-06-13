<?php

namespace Tests\Unit\Middleware;

use App\Url;
use Tests\TestCase;

class UrlHubLinkCheckerTest extends TestCase
{
    /** @test */
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
     */
    public function long_url_already_exist_1()
    {
        $user = $this->admin();
        $long_url = 'https://laravel.com';

        factory(Url::class)->create([
            'user_id'  => $user->id,
            'long_url' => $long_url,
        ]);

        $this->loginAsAdmin();

        $response = $this->post(route('createshortlink'), [
            'long_url' => $long_url,
        ]);
        $response->assertSessionHas('msgLinkAlreadyExists');
    }

    /** @test */
    public function long_url_already_exist_2()
    {
        $long_url = 'https://laravel.com';

        factory(Url::class)->create([
            'user_id'  => null,
            'long_url' => $long_url,
        ]);

        $response = $this->post(route('createshortlink'), [
            'long_url' => $long_url,
        ]);
        $response->assertSessionHas('msgLinkAlreadyExists');
    }
}
