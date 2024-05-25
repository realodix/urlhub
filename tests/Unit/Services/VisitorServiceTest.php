<?php

namespace Tests\Unit\Services;

use App\Services\VisitorService;
use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

#[PHPUnit\Group('services')]
class VisitorServiceTest extends TestCase
{
    private VisitorService $visitorService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->visitorService = app(VisitorService::class);
    }

    #[PHPUnit\Test]
    #[PHPUnit\Group('u-service')]
    public function getRefererHost(): void
    {
        $this->assertSame(null, $this->visitorService->getRefererHost(null));
        $this->assertSame(
            'https://github.com',
            $this->visitorService->getRefererHost('https://github.com/laravel')
        );
        $this->assertSame(
            'http://urlhub.test',
            $this->visitorService->getRefererHost('http://urlhub.test/admin?page=2')
        );
    }
}
