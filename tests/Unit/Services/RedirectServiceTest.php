<?php

namespace Tests\Unit\Services;

use App\Models\Url;
use App\Services\RedirectService;
use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

#[PHPUnit\Group('services')]
class RedirectServiceTest extends TestCase
{
    /**
     * @see \App\Services\RedirectService
     * @see \App\Http\Controllers\RedirectController
     */
    public function testUrlRedirect()
    {
        $url = Url::factory()->create();
        $response = app(RedirectService::class)->execute($url);

        $this->assertEquals($url->destination, $response->getTargetUrl());
        $this->assertEquals(settings()->redirect_status_code, $response->status());
    }

    /**
     * @see \App\Services\RedirectService
     * @see \App\Http\Controllers\RedirectController
     */
    public function testUrlRedirectionHeadersWithMaxAge()
    {
        settings()->fill(['redirect_cache_max_age' => 3600])->save();

        $url = Url::factory()->create();
        $response = app(RedirectService::class)->execute($url);

        $this->assertStringContainsString('max-age=3600', $response->headers->get('Cache-Control'));
    }

    /**
     * @see \App\Services\RedirectService
     * @see \App\Http\Controllers\RedirectController
     */
    public function testUrlRedirectionHeadersWithMaxAgeZero()
    {
        settings()->fill(['redirect_cache_max_age' => 0])->save();

        $url = Url::factory()->create();
        $response = app(RedirectService::class)->execute($url);

        $this->assertStringContainsString('max-age=0', $response->headers->get('Cache-Control'));
        $this->assertStringContainsString('must-revalidate', $response->headers->get('Cache-Control'));
    }
}
