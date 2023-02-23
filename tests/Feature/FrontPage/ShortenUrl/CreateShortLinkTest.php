<?php

namespace Tests\Feature\FrontPage\ShortenUrl;

use App\Models\Url;
use Tests\TestCase;

class CreateShortLinkTest extends TestCase
{
    /**
     * Users shorten the URLs, they don't fill in the custom keyword field. The
     * is_custom column (Urls table) must be filled with 0 / false.
     *
     * @test
     */
    public function shortenUrl(): void
    {
        $longUrl = 'https://laravel.com';
        $response = $this->post(route('su_create'), [
            'long_url' => $longUrl,
        ]);

        $url = Url::whereDestination($longUrl)->first();

        $response->assertRedirectToRoute('su_detail', $url->keyword);
        $this->assertFalse($url->is_custom);
    }

    /**
     * The user shortens the URL and they fill in the custom keyword field. The
     * keyword column (Urls table) must be filled with the keywords requested
     * by the user and the is_custom column must be filled with 1 / true.
     *
     * @test
     */
    public function shortenUrlWithCustomKeyword(): void
    {
        $longUrl = 'https://laravel.com';
        $customKey = 'laravel';

        $response = $this->post(route('su_create'), [
            'long_url'   => $longUrl,
            'custom_key' => $customKey,
        ]);
        $response->assertRedirectToRoute('su_detail', $customKey);

        $url = Url::whereDestination($longUrl)->first();
        $this->assertTrue($url->is_custom);
    }

    /*
    |--------------------------------------------------------------------------
    | URL already exist
    |--------------------------------------------------------------------------
    */

    /**
     * Memastikan URL dengan atau tanpa trailing slash akan dianggap sama.
     *
     * @test
     */
    public function urlsWithOrWithoutSlashesWillBeConsideredTheSame(): void
    {
        $longUrl_1 = 'https://example.com/';
        $longUrl_2 = 'https://example.com';

        $url = Url::factory()->create([
            'user_id'     => null,
            'destination' => $longUrl_1,
        ]);

        $response = $this->post(route('su_create'), [
            'long_url' => $longUrl_2,
        ]);

        $response
            ->assertRedirectToRoute('su_detail', $url->keyword)
            ->assertSessionHas('msgLinkAlreadyExists');

        $this->assertCount(1, Url::all());
    }

    /**
     * User A and User A
     * Tampilkan peringatan bahwa URL sudah ada, dimana ketika dia sudah memiliki
     * URL tersebut.
     *
     * @test
     */
    public function longUrlAlreadyExist(): void
    {
        $url = Url::factory()->create();

        $response = $this->actingAs($url->author)
            ->post(route('su_create'), [
                'long_url' => $url->destination,
            ]);

        $response
            ->assertRedirectToRoute('su_detail', $url->keyword)
            ->assertSessionHas('msgLinkAlreadyExists');

        $this->assertCount(1, Url::all());
    }

    /**
     * Guest A and guest B
     * Tampilkan peringatan bahwa URL sudah ada, dimana ketika user guest lainnya sudah
     * memiliki url tersebut.
     *
     * @test
     */
    public function urlAlreadyExist_guestWithAnotherGuest(): void
    {
        $url = Url::factory()->create(['user_id' => Url::GUEST_ID]);

        $response = $this->post(route('su_create'), [
            'long_url' => $url->destination,
        ]);

        $response
            ->assertRedirectToRoute('su_detail', $url->keyword)
            ->assertSessionHas('msgLinkAlreadyExists');

        $this->assertCount(1, Url::all());
    }

    /**
     * User A and User B
     * Ketika User A sudah memiliki URL dan User B membuat URL yang sama, maka
     * peringatan tidak perlu ditampilkan.
     *
     * @test
     */
    public function longUrlAlreadyExistsButStillAccepted1(): void
    {
        $user = $this->normalUser();
        $urlFromOtherUsers = Url::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('su_create'), [
                'long_url' => $urlFromOtherUsers->destination,
            ]);

        $url = $user->urls()->first();

        $response->assertRedirectToRoute('su_detail', $url->keyword);
        $this->assertCount(2, Url::all());
    }

    /**
     * Authen User and Guest
     *
     * Ketika URL sudah ada (dibuat oleh authen user), lalu guest membuat shorlink
     * dengan URL yang sama, maka peringatan tidak perlu ditampilkan.
     *
     * @test
     */
    public function longUrlAlreadyExistsButStillAccepted2(): void
    {
        $url = Url::factory()->create();

        $response = $this->post(route('su_create'), [
            'long_url' => $url->destination,
        ]);

        $url = Url::whereUserId(null)->first();

        $response->assertRedirectToRoute('su_detail', $url->keyword);
        $this->assertCount(2, Url::all());
    }

    /**
     * Guest user and authen user
     *
     * Ketika URL sudah ada (dibuat oleh Guest), lalu salah satu User membuat shorlink
     * dengan URL yang sama, maka peringatan tidak perlu ditampilkan.
     *
     * @test
     */
    public function longUrlAlreadyExistsButStillAccepted3(): void
    {
        $user = $this->normalUser();
        $url = Url::factory()->create(['user_id' => Url::GUEST_ID]);

        $response = $this->actingAs($user)
            ->post(route('su_create'), [
                'long_url' => $url->destination,
            ]);

        $url = $user->urls()->first();

        $response->assertRedirectToRoute('su_detail', $url->keyword);
        $this->assertCount(2, Url::all());
    }

    /*
    |--------------------------------------------------------------------------
    | Custom key already exist
    |--------------------------------------------------------------------------
    */

    /**
     * This test is to make sure that the custom key is not used by other users.
     *
     * @test
     */
    public function customKeyAlreadyExist(): void
    {
        $url = Url::factory()->create(['user_id' => Url::GUEST_ID]);
        $customKey = 'laravel';

        $response = $this->post(route('su_create'), [
            'long_url'   => $url->destination,
            'custom_key' => $customKey,
        ]);
        $response->assertRedirectToRoute('su_detail', $url->keyword);

        $response2 = $this->get(route('home').'/'.$customKey);
        $response2->assertNotFound();
        $this->assertCount(1, Url::all());
    }

    /**
     * This test is to make sure that the custom key is not used by other users.
     *
     * @test
     */
    public function customKeyAlreadyExist2(): void
    {
        $url = Url::factory()->create();

        $response = $this->post(route('su_create'), [
            'long_url'   => 'https://laravel-news.com',
            'custom_key' => $url->keyword,
        ]);

        $response
            ->assertRedirectToRoute('home')
            ->assertSessionHasErrors('custom_key');

        $this->assertCount(1, Url::all());
    }

    /**
     * With authenticated user.
     * This test is to make sure that the custom key is not used by other users.
     *
     * @test
     */
    public function customKeyAlreadyExist3(): void
    {
        $url = Url::factory()->create();

        $response = $this->actingAs($this->normalUser())
            ->post(route('su_create'), [
                'long_url'   => 'https://laravel-news.com',
                'custom_key' => $url->keyword,
            ]);

        $response
            ->assertRedirectToRoute('home')
            ->assertSessionHasErrors('custom_key');

        $this->assertCount(1, Url::all());
    }
}
