<?php

namespace Tests\Unit\Services;

use App\Services\UHubLinkService;
use Tests\TestCase;

class UHubLinkServiceTest extends TestCase
{
    private UHubLinkService $uHubLinkService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->uHubLinkService = app(UHubLinkService::class);
    }

    /**
     * @test
     */
    public function title()
    {
        $expected = 'example123456789.com - Untitled';
        $actual = $this->uHubLinkService->title('https://example123456789.com');
        $this->assertSame($expected, $actual);

        $expected = 'www.example123456789.com - Untitled';
        $actual = $this->uHubLinkService->title('https://www.example123456789.com');
        $this->assertSame($expected, $actual);
    }
}
