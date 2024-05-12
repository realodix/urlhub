<?php

namespace Tests\Unit\Services;

use App\Services\VisitorService;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class VisitorServiceTest extends TestCase
{
    private VisitorService $visitorService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->visitorService = app(VisitorService::class);
    }

    #[Test]
    #[Group('u-service')]
    public function getRefererHost(): void
    {
        $this->assertSame(null, $this->visitorService->getRefererHost(null));
        $this->assertSame(
            'https://github.com',
            $this->visitorService->getRefererHost('https://github.com/laravel')
        );
    }
}
