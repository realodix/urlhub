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
    public function keywordBlacklistFail($value): void
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
     * Persingkat URL ketika generator string sudah tidak dapat menghasilkan keyword
     * unik (semua keyword sudah terpakai). UrlHub harus mencegah user untuk melakukan
     * penyingkatan URL.
     *
     * @test
     */
    public function idleCapacityIsZero(): void
    {
        config(['urlhub.hash_length' => 0]);

        $response = $this->post(route('su_create'), ['long_url' => 'https://laravel.com']);

        $response
            ->assertRedirectToRoute('home')
            ->assertSessionHas('flash_error');
    }

    public static function keywordBlacklistFailDataProvider(): array
    {
        return [
            ['login'],
            ['register'],
            ['css'], // urlhub.reserved_keyword
        ];
    }
}
