<?php

namespace Tests\Unit\Models;

use App\Models\Url;
use App\Models\Visit;
use Tests\TestCase;

class VisitsTest extends TestCase
{
    /**
     * @test
     * @group u-model
     */
    public function belongs_to_url()
    {
        $visit = factory(Visit::class)->create([
            'url_id' => function () {
                return factory(Url::class)->create()->id;
            },
        ]);

        $this->assertTrue($visit->url()->exists());
    }
}
