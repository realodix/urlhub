<?php

namespace Tests\Unit\Services;

use App\Services\UrlService;
use Tests\TestCase;

class UrlServiceTest extends TestCase
{
    private UrlService $UrlService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->UrlService = app(UrlService::class);
    }

    /** @test */
    public function title(): void
    {
        $expected = 'example123456789.com - Untitled';
        $actual = $this->UrlService->title('https://example123456789.com');
        $this->assertSame($expected, $actual);

        $expected = 'www.example123456789.com - Untitled';
        $actual = $this->UrlService->title('https://www.example123456789.com');
        $this->assertSame($expected, $actual);
    }

    /**
     * When config('urlhub.web_title') set `false`, title() should return
     * 'No Title' if the title is empty
     *
     * @test
     */
    public function titleShouldReturnNoTitle(): void
    {
        config(['urlhub.web_title' => false]);

        $expected = 'No Title';
        $actual = $this->UrlService->title('https://example123456789.com');
        $this->assertSame($expected, $actual);
    }
}
