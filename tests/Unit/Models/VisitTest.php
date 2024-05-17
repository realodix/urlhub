<?php

namespace Tests\Unit\Models;

use App\Models\Url;
use App\Models\Visit;
use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

#[PHPUnit\Group('model')]
class VisitTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    #[PHPUnit\Test]
    public function belongsToUrlModel(): void
    {
        $visit = Visit::factory()->create();

        $this->assertEquals(1, $visit->url->count());
        $this->assertInstanceOf(Url::class, $visit->url);
    }
}
