<?php

namespace Tests\Feature\FrontPage\ShortenUrl;

use App\Enums\UserType;
use App\Models\Url;
use App\Services\KeyGeneratorService;
use App\Services\UserService;
use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

#[\PHPUnit\Framework\Attributes\Group('front-page')]
class CreateShortLinkTest extends TestCase
{
    /**
     * Users shorten the URLs, they don't fill in the custom keyword field. The
     * is_custom column (Urls table) must be filled with 0 / false.
     */
    #[PHPUnit\Test]
    #[PHPUnit\Group('forward-query')]
    public function shortenUrl(): void
    {
        $this->partialMock(UserService::class)
            ->shouldReceive(['signature' => 'mocked_signature']);

        $longUrl = 'https://laravel.com';
        $response = $this->actingAs($this->basicUser())
            ->post(route('link.create'), ['long_url' => $longUrl]);
        $url = Url::where('destination', $longUrl)->first();

        $response->assertRedirectToRoute('link_detail', $url->keyword);
        $this->assertFalse($url->is_custom);
        $this->assertTrue($url->forward_query);
        $this->assertSame(UserType::User, $url->user_type);
    }

    #[PHPUnit\Group('forward-query')]
    public function testGuestCanShortenUrl(): void
    {
        $this->partialMock(UserService::class)
            ->shouldReceive(['signature' => 'mocked_signature']);

        $longUrl = 'https://laravel.com';
        $response = $this->post(route('link.create'), ['long_url' => $longUrl]);
        $url = Url::where('destination', $longUrl)->first();

        $response->assertRedirectToRoute('link_detail', $url->keyword);
        $this->assertFalse($url->is_custom);
        $this->assertFalse($url->forward_query);
        $this->assertSame(UserType::Guest, $url->user_type);
    }

    /**
     * The user shortens the URL and they fill in the custom keyword field. The
     * keyword column (Urls table) must be filled with the keywords requested
     * by the user and the is_custom column must be filled with 1 / true.
     */
    public function testShortenUrlWithCustomKeyword(): void
    {
        $this->partialMock(UserService::class)
            ->shouldReceive(['signature' => 'mocked_signature']);

        $longUrl = 'https://example.com/shorten-url-with-custom-keyword';
        $customKey = 'foobar';
        $response = $this->actingAs($this->basicUser())
            ->post(route('link.create'), [
                'long_url' => $longUrl,
                'custom_key' => $customKey,
            ]);

        $response->assertRedirectToRoute('link_detail', $customKey);
        $url = Url::where('destination', $longUrl)->first();
        $this->assertTrue($url->is_custom);
    }

    /**
     * Shorten urls when the remaining space is not enough.
     *
     * Shorten the URL when the string generator can no longer generate unique
     * keywords (all keywords have been used). UrlHub must prevent users from
     * shortening URLs.
     *
     * @see App\Http\Controllers\LinkController::create()
     * @see App\Http\Middleware\UrlHubLinkChecker
     * @see App\Services\KeyGeneratorService::remainingCapacity()
     */
    public function testShortenUrlWhenRemainingSpaceIsNotEnough(): void
    {
        $this->mock(KeyGeneratorService::class)
            ->shouldReceive(['remainingCapacity' => 0]);

        $response = $this->actingAs($this->basicUser())
            ->post(route('link.create'), ['long_url' => 'https://laravel.com']);
        $response
            ->assertRedirectToRoute('home')
            ->assertSessionHas('flash_error');
    }

    public function testShortenUrlWithInternalLink(): void
    {
        $response = $this->actingAs($this->basicUser())
            ->post(route('link.create'), ['long_url' => request()->getHost()]);
        $response
            ->assertRedirectToRoute('home')
            ->assertSessionHas('flash_error');
        $this->assertCount(0, Url::all());

        $response = $this->actingAs($this->basicUser())
            ->post(route('link.create'), ['long_url' => config('app.url')]);
        $response
            ->assertRedirectToRoute('home')
            ->assertSessionHas('flash_error');
        $this->assertCount(0, Url::all());
    }

    #[PHPUnit\Test]
    public function shortenUrlFailsAndShowsErrorPageWhenMaxAttemptsReached(): void
    {
        $this->mock(KeyGeneratorService::class, function ($mock) {
            // Allow the UrlHubLinkChecker middleware to pass
            $mock->shouldReceive('remainingCapacity')->andReturn(1);

            // Simulate that the generate() method will always fail after
            // trying multiple times and throw an exception.
            $mock->shouldReceive('generate')->once()
                ->andThrow(new \App\Exceptions\CouldNotGenerateUniqueKeyException);
        });

        $longUrl = 'https://example.com/some-url-that-triggers-failure';
        $response = $this->post(route('link.create'), ['long_url' => $longUrl]);

        $response->assertStatus(503);
        $response->assertViewIs('errors.key_generation_failed');
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

        $response = $this->actingAs($this->basicUser())
            ->post(route('link.create'), [
                'long_url' => 'https://laravel-news.com',
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
                'long_url' => 'https://laravel-news.com',
                'custom_key' => $url->keyword,
            ]);

        $response
            ->assertRedirectToRoute('home')
            ->assertSessionHasErrors('custom_key');

        $this->assertCount(1, Url::all());
    }
}
