<?php

namespace Tests\Unit\Controllers;

use App\Models\Url;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[\PHPUnit\Framework\Attributes\Group('controller')]
class UrlControllerTest extends TestCase
{
    /**
     * When the guest (users who are not logged in) shortens the URL, the user_id column
     * (Urls table) must be filled with a null value.
     */
    #[Test]
    public function guestShortenURL(): void
    {
        $longUrl = 'https://laravel.com';

        $this->post(route('su_create'), ['long_url' => $longUrl]);

        $url = Url::whereDestination($longUrl)->first();
        $this->assertSame(null, $url->user_id);
    }

    /**
     * When the User shortens the URL, the user_id column (Urls table) must be filled with
     * the authenticated user id.
     */
    #[Test]
    public function userShortenURL(): void
    {
        $user = $this->normalUser();
        $longUrl = 'https://laravel.com';

        $this->actingAs($user)
            ->post(route('su_create'), ['long_url' => $longUrl]);

        $url = Url::whereDestination($longUrl)->first();
        $this->assertSame($user->id, $url->user_id);
    }

    #[Test]
    public function showDetail(): void
    {
        $url = Url::factory()->create();

        $response = $this->get(route('su_detail', $url->keyword));

        $response->assertStatus(200);
    }
}
