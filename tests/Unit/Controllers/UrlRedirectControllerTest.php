<?php

namespace Tests\Unit\Controllers;

use App\Models\Url;
use App\Models\Visit;
use App\Services\UrlRedirection;
use Tests\TestCase;

#[\PHPUnit\Framework\Attributes\Group('controller')]
class UrlRedirectControllerTest extends TestCase
{
    public function testUrlRedirection(): void
    {
        $url = Url::factory()->create();

        $response = $this->get(route('home') . '/' . $url->keyword);
        $response->assertRedirect($url->destination)
            ->assertStatus(config('urlhub.redirect_status_code'));

        $this->assertCount(1, Visit::all());
    }

    /**
     * @see \App\Services\UrlRedirection
     * @see \App\Http\Controllers\UrlRedirectController
     */
    public function testUrlRedirectionHeadersWithMaxAge()
    {
        config(['urlhub.redirect_cache_max_age' => 3600]);

        $url = Url::factory()->create();
        $response = app(UrlRedirection::class)->execute($url);

        $this->assertStringContainsString('max-age=3600', $response->headers->get('Cache-Control'));
    }

    /**
     * @see \App\Services\UrlRedirection
     * @see \App\Http\Controllers\UrlRedirectController
     */
    public function testUrlRedirectionHeadersWithMaxAgeZero()
    {
        config(['urlhub.redirect_cache_max_age' => 0]);

        $url = Url::factory()->create();
        $response = app(UrlRedirection::class)->execute($url);

        $this->assertStringContainsString('max-age=0', $response->headers->get('Cache-Control'));
        $this->assertStringContainsString('must-revalidate', $response->headers->get('Cache-Control'));
    }
}
