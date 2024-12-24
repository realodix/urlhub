<?php

namespace Tests\Unit\Controllers;

use App\Models\Url;
use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

#[PHPUnit\Group('controller')]
class UrlControllerTest extends TestCase
{
    /**
     * When the guest (users who are not logged in) shortens the URL, the user_id column
     * (Urls table) must be filled with a null value.
     */
    #[PHPUnit\Test]
    public function guestShortenURL(): void
    {
        $longUrl = 'https://laravel.com';

        $this->post(route('link.create'), ['long_url' => $longUrl]);

        $url = Url::whereDestination($longUrl)->first();
        $this->assertSame(null, $url->user_id);
    }

    /**
     * When the User shortens the URL, the user_id column (Urls table) must be filled with
     * the authenticated user id.
     */
    #[PHPUnit\Test]
    public function userShortenURL(): void
    {
        $user = $this->basicUser();
        $longUrl = 'https://laravel.com';

        $this->actingAs($user)
            ->post(route('link.create'), ['long_url' => $longUrl]);

        $url = Url::whereDestination($longUrl)->first();
        $this->assertSame($user->id, $url->user_id);
    }

    #[PHPUnit\Test]
    public function showDetail(): void
    {
        $url = Url::factory()->create();

        $response = $this->get(route('link_detail', $url->keyword));

        $response->assertStatus(200);
    }
}
