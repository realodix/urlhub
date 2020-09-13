<?php

namespace Tests\Unit\Models;

use App\Models\Url;
use App\Models\Visit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VisitTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @group u-model
     */
    public function belongs_to_url()
    {
        $visit = Visit::factory()->create([
            'url_id' => function () {
                return Url::factory()->create()->id;
            },
        ]);

        $this->assertTrue($visit->url()->exists());
    }
}
