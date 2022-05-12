<?php

namespace Tests\Feature;

use App\Models\Url;
use Tests\TestCase;

/**
 * Front-End Test.
 */
class UrlFeTest extends TestCase
{
    /**
     * Users shorten the URLs, they don't fill in the custom keyword field. The
     * is_custom column (Urls table) must be filled with 0 / false.
     *
     * @test
     */
    public function shortenUrl()
    {
        $longUrl = 'https://laravel.com';
        $response = $this->post(route('createshortlink'), [
            'long_url' => $longUrl,
        ]);

        $url = Url::whereLongUrl($longUrl)->first();

        $response->assertRedirect(route('short_url.stats', $url->keyword));
        $this->assertFalse($url->is_custom);
    }

    /**
     * The user shortens the URL and they fill in the custom keyword field. The
     * keyword column (Urls table) must be filled with the keywords requested
     * by the user and the is_custom column must be filled with 1 / true.
     *
     * @test
     */
    public function shortenUrlWithCustomKeyword()
    {
        $longUrl = 'https://laravel.com';
        $customKey = 'laravel';

        $response = $this->post(route('createshortlink'), [
            'long_url'   => $longUrl,
            'custom_key' => $customKey,
        ]);
        $response->assertRedirect(route('short_url.stats', $customKey));

        $url = Url::whereLongUrl($longUrl)->first();
        $this->assertTrue($url->is_custom);
    }

    /**
     * Guest A and guest B.
     *
     * @test
     */
    public function longUrlAlreadyExist()
    {
        $url = Url::factory()->create([
            'user_id' => null,
        ]);

        $response = $this->post(route('createshortlink'), [
            'long_url' => $url->long_url,
        ]);

        $response
            ->assertRedirect(route('short_url.stats', $url->keyword))
            ->assertSessionHas('msgLinkAlreadyExists');

        $this->assertCount(1, Url::all());
    }

    /**
     * Authen user A and authen user A.
     *
     * @test
     */
    public function longUrlAlreadyExist2()
    {
        $user = $this->admin();

        $url = Url::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->loginAsAdmin();

        $response = $this->post(route('createshortlink'), [
            'long_url' => $url->long_url,
        ]);

        $response
            ->assertRedirect(route('short_url.stats', $url->keyword))
            ->assertSessionHas('msgLinkAlreadyExists');

        $this->assertCount(1, Url::all());
    }

    /**
     * Guest and authen user.
     *
     * @test
     */
    public function longUrlAlreadyExistsButStillAccepted()
    {
        $url = Url::factory()->create();

        $response = $this->post(route('createshortlink'), [
            'long_url' => $url->long_url,
        ]);

        $url = Url::whereUserId(null)->first();

        $response->assertRedirect(route('short_url.stats', $url->keyword));

        $this->assertCount(2, Url::all());
    }

    /**
     * Authen user A and authen user B.
     *
     * @test
     */
    public function longUrlAlreadyExistsButStillAccepted2()
    {
        $user = $this->admin();
        $user2 = $this->nonAdmin();

        $url = Url::factory()->create([
            'user_id' => $user2->id,
        ]);

        $this->loginAsAdmin();

        $response = $this->post(route('createshortlink'), [
            'long_url' => $url->long_url,
        ]);

        $url = Url::whereUserId($user->id)->first();

        $response->assertRedirect(route('short_url.stats', $url->keyword));

        $this->assertCount(2, Url::all());
    }

    /**
     * Authen user and guest.
     *
     * @test
     */
    public function longUrlAlreadyExistsButStillAccepted3()
    {
        $user = $this->admin();

        $url = Url::factory()->create([
            'user_id' => null,
        ]);

        $this->loginAsAdmin();

        $response = $this->post(route('createshortlink'), [
            'long_url' => $url->long_url,
        ]);

        $url = Url::whereUserId($user->id)->first();

        $response->assertRedirect(route('short_url.stats', $url->keyword));

        $this->assertCount(2, Url::all());
    }

    /** @test */
    public function duplicate()
    {
        $this->loginAsAdmin();

        $url = Url::factory()->create([
            'user_id' => $this->admin()->id,
        ]);

        $this->post(route('createshortlink'), [
            'long_url' => $url->long_url,
        ]);

        $this->from(route('short_url.stats', $url->keyword))
             ->get(route('duplicate', $url->keyword));

        $this->assertCount(2, Url::all());
    }

    /** @test */
    public function createShortUrlWithWrongUrlFormat()
    {
        $response = $this->post(route('createshortlink'), [
            'long_url' => 'wrong-url-format',
        ]);

        $response
            ->assertRedirect(route('home'))
            ->assertSessionHasErrors('long_url');
    }

    /*
    |--------------------------------------------------------------------------
    | Custom Short URLs
    |--------------------------------------------------------------------------
    |
    | Short URL with custom keyword and long url already in database.
    */

    /** @test */
    public function cstLongUrlAlreadyExist()
    {
        $url = Url::factory()->create([
            'user_id' => null,
        ]);

        $customKey = 'laravel';

        $response = $this->post(route('createshortlink'), [
            'long_url'   => $url->long_url,
            'custom_key' => $customKey,
        ]);
        $response->assertRedirect(
            route('short_url.stats', $url->keyword)
        );

        $response2 = $this->get(route('home').'/'.$customKey);
        $response2->assertNotFound();
    }

    /** @test */
    public function cstLongUrlAlreadyExist2()
    {
        $url = Url::factory()->create([
            'user_id' => null,
        ]);

        $this->loginAsNonAdmin();

        $customKey = 'laravel';

        $response = $this->post(route('createshortlink'), [
            'long_url'   => $url->long_url,
            'custom_key' => $customKey,
        ]);

        $response->assertRedirect(
            route('short_url.stats', $customKey)
        );

        $response2 = $this->get(route('home').'/'.$customKey);
        $response2->assertRedirect($url->long_url);

        $this->assertCount(2, Url::all());
    }

    /** @test */
    public function cstCstKeywordAlreadyExist()
    {
        $url = Url::factory()->create();

        $response = $this->post(route('createshortlink'), [
            'long_url'   => 'https://laravel-news.com',
            'custom_key' => $url->keyword,
        ]);

        $response
            ->assertRedirect(route('home'))
            ->assertSessionHasErrors('custom_key');

        $this->assertCount(1, Url::all());
    }

    /**
     * With authenticated user.
     *
     * @test
     */
    public function cstCstKeywordAlreadyExist2()
    {
        $url = Url::factory()->create();

        $this->loginAsNonAdmin();

        $response = $this->post(route('createshortlink'), [
            'long_url'   => 'https://laravel-news.com',
            'custom_key' => $url->keyword,
        ]);

        $response
            ->assertRedirect(route('home'))
            ->assertSessionHasErrors('custom_key');

        $this->assertCount(1, Url::all());
    }
}
