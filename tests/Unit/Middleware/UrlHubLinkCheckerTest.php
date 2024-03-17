<?php

namespace Tests\Unit\Middleware;

use PHPUnit\Framework\Attributes\{DataProvider, Test};
use Tests\TestCase;

class UrlHubLinkCheckerTest extends TestCase
{
    /**
     * @param array $value
     */
    #[Test]
    #[DataProvider('keywordBlacklistFailDataProvider')]
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
     */
    #[Test]
    public function remainingCapacityIsZero(): void
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
