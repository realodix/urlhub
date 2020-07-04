<?php

namespace Tests\Unit\Models;

use App\Models\Url;
use App\Models\Visits;
use Tests\TestCase;

class VisitStatTest extends TestCase
{
    /**
     * @test
     * @group u-model
     */
    public function belongs_to_url()
    {
        $visitStat = factory(Visits::class)->create([
            'url_id' => function () {
                return factory(Url::class)->create()->id;
            },
        ]);

        $this->assertTrue($visitStat->url()->exists());
    }
}
