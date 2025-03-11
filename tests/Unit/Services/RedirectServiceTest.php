<?php

namespace Tests\Unit\Services;

use App\Models\Url;
use App\Services\RedirectService;
use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

/**
 * @see \App\Services\RedirectService
 * @see \App\Http\Controllers\RedirectController
 */
#[PHPUnit\Group('services')]
class RedirectServiceTest extends TestCase
{
    public function testUrlRedirect()
    {
        $url = Url::factory()->create();
        $response = app(RedirectService::class)->execute($url);

        $this->assertEquals($url->destination, $response->getTargetUrl());
        $this->assertEquals(config('urlhub.redirection_status_code'), $response->status());
    }

    #[PHPUnit\Group('forward-query')]
    #[PHPUnit\DataProvider('urlWithQueryStringDataProvider')]
    public function testUrlWithQueryString(string $destination, array $incomingQuery, string $expectedDestination): void
    {
        $query = app(RedirectService::class)->resolveQuery($destination, $incomingQuery);

        $this->assertSame($expectedDestination, $query);
    }

    public static function urlWithQueryStringDataProvider(): array
    {
        return [
            'new_query_param' => [
                'https://example.com', ['a' => '1'], 'https://example.com?a=1',
            ],
            'new_query_params' => [
                'https://example.com', ['a' => '1', 'b' => '2'], 'https://example.com?a=1&b=2',
            ],
            'existing_query_params' => [
                'https://example.com?a=1', ['a' => '1'], 'https://example.com?a=1',
            ],
            'existing_and_new_query_params' => [
                'https://example.com?x=y', ['a' => '1', 'b' => '2'], 'https://example.com?x=y&a=1&b=2',
            ],
            'fragment' => [
                'https://example.com#section', ['a' => '1'], 'https://example.com?a=1#section',
            ],
            'special_chars' => [ // space encoding
                'https://example.com?a=b c', ['d' => 'e'], 'https://example.com?a=b%20c&d=e',
            ],
        ];
    }

    #[PHPUnit\Group('forward-query')]
    #[PHPUnit\DataProvider('urlWithDuplicateQueryStringDataProvider')]
    public function testUrlWithDuplicateQueryString(string $destination, array $incomingQuery, string $expectedDestination): void
    {
        $query = app(RedirectService::class)->resolveQuery($destination, $incomingQuery);

        $this->assertSame($expectedDestination, $query);
    }

    /**
     * Reference:
     * - https://dub.co/help/article/parameter-passing
     * - https://help.short.io/en/articles/8880292
     */
    public static function urlWithDuplicateQueryStringDataProvider(): array
    {
        return [
            'duplicate_params' => [
                'https://example.com?a=1', ['a' => '2'], 'https://example.com?a=2',
            ],
            'duplicate_params_and_new_query_params' => [
                'https://example.com?a=1', ['a' => '2', 'b' => '2'], 'https://example.com?a=2&b=2',
            ],
            'duplicate_params_and_existing_duplicate_query_params' => [
                'https://example.com?a=1&a=2', ['a' => '2', 'b' => 'b1', 'b' => 'b2'], 'https://example.com?a=2&b=b2',
            ],
        ];
    }

    public function testUrlRedirectionHeadersWithMaxAge()
    {
        settings()->fill(['redirect_cache_max_age' => 3600])->save();

        $url = Url::factory()->create();
        $response = app(RedirectService::class)->execute($url);

        $this->assertStringContainsString('max-age=3600', $response->headers->get('Cache-Control'));
    }

    public function testUrlRedirectionHeadersWithMaxAgeZero()
    {
        settings()->fill(['redirect_cache_max_age' => 0])->save();

        $url = Url::factory()->create();
        $response = app(RedirectService::class)->execute($url);

        $this->assertStringContainsString('max-age=0', $response->headers->get('Cache-Control'));
        $this->assertStringContainsString('must-revalidate', $response->headers->get('Cache-Control'));
    }
}
