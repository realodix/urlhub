<?php

namespace Tests\Unit\Models;

use App\Models\Url;
use App\Models\Visit;
use App\Services\UserService;
use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

#[PHPUnit\Group('model')]
class VisitTest extends TestCase
{
    private Visit $visit;

    protected function setUp(): void
    {
        parent::setUp();

        $this->visit = new Visit;
    }

    public function testFactory(): void
    {
        $m = Visit::factory()->guest()->create();

        $this->assertSame(\App\Enums\UserType::Guest, $m->user_type);
    }

    #[PHPUnit\Test]
    public function belongsToUrlModel(): void
    {
        $visit = Visit::factory()->create();

        $this->assertEquals(1, $visit->url->count());
        $this->assertInstanceOf(Url::class, $visit->url);
    }

    #[PHPUnit\Test]
    public function isFirstClick(): void
    {
        // First visit
        $url = Url::factory()->create();
        $this->assertTrue($this->visit->isFirstClick($url));

        // Second visit and so on
        $url = Url::factory()->create();
        Visit::factory()->for($url)->create([
            'user_uid' => app(UserService::class)->signature(),
        ]);
        $this->assertFalse($this->visit->isFirstClick($url));
    }
}
