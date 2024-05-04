<?php

namespace Tests\Unit\Middleware;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UrlHubLinkCheckerTest extends TestCase
{
    /**
     * Persingkat URL ketika generator string sudah tidak dapat menghasilkan keyword
     * unik (semua keyword sudah terpakai). UrlHub harus mencegah user untuk melakukan
     * penyingkatan URL.
     */
    #[Test]
    public function remainingCapacityIsZero(): void
    {
        config(['urlhub.keyword_length' => 0]);

        $response = $this->post(route('su_create'), ['long_url' => 'https://laravel.com']);

        $response
            ->assertRedirectToRoute('home')
            ->assertSessionHas('flash_error');
    }
}
