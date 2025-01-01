<?php

namespace Tests\Feature\FrontPage\ShortenUrl;

use App\Models\Url;
use Tests\TestCase;

#[\PHPUnit\Framework\Attributes\Group('front-page')]
class CreateShortLinkTest extends TestCase
{
    /**
     * Users shorten the URLs, they don't fill in the custom keyword field. The
     * is_custom column (Urls table) must be filled with 0 / false.
     */
    public function testShortenUrl(): void
    {
        $longUrl = 'https://laravel.com';
        $response = $this->post(route('link.create'), [
            'long_url' => $longUrl,
        ]);

        $url = Url::whereDestination($longUrl)->first();

        $response->assertRedirectToRoute('link_detail', $url->keyword);
        $this->assertFalse($url->is_custom);
    }

    /**
     * The user shortens the URL and they fill in the custom keyword field. The
     * keyword column (Urls table) must be filled with the keywords requested
     * by the user and the is_custom column must be filled with 1 / true.
     */
    public function testShortenUrlWithCustomKeyword(): void
    {
        $longUrl = 'https://t.co';

        $customKey = 'foobar';
        config(['urlhub.keyword_length' => strlen($customKey) + 1]);
        $response = $this->post(route('link.create'), [
            'long_url'   => $longUrl,
            'custom_key' => $customKey,
        ]);
        $response->assertRedirectToRoute('link_detail', $customKey);
        $url = Url::whereDestination($longUrl)->first();
        $this->assertTrue($url->is_custom);

        $customKey = 'barfoo';
        config(['urlhub.keyword_length' => strlen($customKey) - 1]);
        $response = $this->post(route('link.create'), [
            'long_url'   => $longUrl,
            'custom_key' => $customKey,
        ]);
        $response->assertRedirectToRoute('link_detail', $customKey);
        $url = Url::whereDestination($longUrl)->first();
        $this->assertTrue($url->is_custom);
    }

    /**
     * Shorten urls when the remaining space is not enough.
     *
     * Shorten the URL when the string generator can no longer generate unique
     * keywords (all keywords have been used). UrlHub must prevent users from
     * shortening URLs.
     *
     * @see App\Http\Controllers\UrlController::create()
     * @see App\Http\Middleware\UrlHubLinkChecker
     */
    public function testShortenUrlWhenRemainingSpaceIsNotEnough(): void
    {
        config(['urlhub.keyword_length' => 0]);
        $response = $this->post(route('link.create'), ['long_url' => 'https://laravel.com']);
        $response
            ->assertRedirectToRoute('home')
            ->assertSessionHas('flash_error');
    }

    /*
    |--------------------------------------------------------------------------
    | Custom key already exist
    |--------------------------------------------------------------------------
    */

    /**
     * This test is to make sure that the custom key is not used by other users.
     */
    public function testCustomKeyAlreadyExist(): void
    {
        $url = Url::factory()->create();

        $response = $this->post(route('link.create'), [
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
     */
    public function testCustomKeyAlreadyExist2(): void
    {
        $url = Url::factory()->create();

        $response = $this->actingAs($this->basicUser())
            ->post(route('link.create'), [
                'long_url'   => 'https://laravel-news.com',
                'custom_key' => $url->keyword,
            ]);

        $response
            ->assertRedirectToRoute('home')
            ->assertSessionHasErrors('custom_key');

        $this->assertCount(1, Url::all());
    }
}
