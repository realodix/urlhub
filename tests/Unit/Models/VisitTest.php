<?php

namespace Tests\Unit\Models;

use App\Models\{Url, Visit};
use PHPUnit\Framework\Attributes\{Group, Test};
use Tests\TestCase;

class VisitTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    #[Test]
    #[Group('u-model')]
    public function belongsToUrlModel(): void
    {
        $visit = Visit::factory()->create();

        $this->assertEquals(1, $visit->url->count());
        $this->assertInstanceOf(Url::class, $visit->url);
    }
}
