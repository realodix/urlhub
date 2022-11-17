<?php

namespace Tests\Unit\Models;

use App\Models\Url;
use App\Models\Visit;
use Tests\TestCase;

class VisitTest extends TestCase
{
    /**
     * @test
     * @group u-model
     */
    public function belongsToUrl()
    {
        $visit = Visit::factory()->create([
            'url_id' => function () {
                return Url::factory()->create()->id;
            },
        ]);

        $this->assertTrue($visit->url()->exists());
    }
}
