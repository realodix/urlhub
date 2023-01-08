<?php

namespace Tests\Unit\Middleware;

use Tests\TestCase;

class UrlHubLinkCheckerTest extends TestCase
{
    /**
     * @test
     * @dataProvider keywordBlacklistFailDataProvider
     *
     * @param array $value
     */
    public function keywordBlacklistFail($value)
    {
        $response = $this->post(route('su_create'), [
            'long_url' => 'https://laravel.com',
            'custom_key' => $value,
        ]);

        $response
            ->assertRedirectToRoute('home')
            ->assertSessionHas('flash_error');
    }

    /**
     * Shorten the url when the random string generator maxCapacity is full.
     * UrlHub must prevent URL shortening.
     *
     * @test
     */
    public function idleCapacityZero()
    {
        config(['urlhub.hash_length' => 0]);

        $response = $this->post(route('su_create'), [
            'long_url' => 'https://laravel.com',
        ]);

        $response
            ->assertRedirectToRoute('home')
            ->assertSessionHas('flash_error');
    }

    public function keywordBlacklistFailDataProvider()
    {
        return [
            ['login'],
            ['register'],
            ['css'], // urlhub.reserved_keyword
        ];
    }
}
