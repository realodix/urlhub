<?php

namespace Tests\Unit\Services;

use App\Models\Url;
use App\Models\Visit;
use App\Services\UserService;
use App\Services\VisitService;
use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

#[PHPUnit\Group('services')]
class VisitServiceTest extends TestCase
{
    #[PHPUnit\Test]
    public function isFirstClick(): void
    {
        $visitor = app(VisitService::class);

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
        $visitor = app(VisitService::class);

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
