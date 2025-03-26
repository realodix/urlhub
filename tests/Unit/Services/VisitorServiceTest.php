<?php

namespace Tests\Unit\Services;

use App\Models\Url;
use App\Models\Visit;
use App\Services\UserService;
use App\Services\VisitorService;
use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

#[PHPUnit\Group('services')]
class VisitorServiceTest extends TestCase
{
    #[PHPUnit\Test]
    public function isFirstClick(): void
    {
        $visitor = app(VisitorService::class);

        // First visit
        $url = Url::factory()->create();
        $this->assertTrue($visitor->isFirstClick($url));

        // Second visit and so on
        $url = Url::factory()->create();
        Visit::factory()->for($url)->create([
            'user_uid' => app(UserService::class)->signature(),
        ]);
        $this->assertFalse($visitor->isFirstClick($url));
    }

    #[PHPUnit\Test]
    public function getRefererHost(): void
    {
        $visitor = app(VisitorService::class);

        $this->assertSame(null, $visitor->getRefererHost(null));
        $this->assertSame(
            'https://github.com',
            $visitor->getRefererHost('https://github.com/laravel'),
        );
        $this->assertSame(
            'http://urlhub.test',
            $visitor->getRefererHost('http://urlhub.test/admin?page=2'),
        );
    }
}
