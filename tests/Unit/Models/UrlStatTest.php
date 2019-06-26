<?php

namespace Tests\Unit\Models;

use App\Url;
use App\UrlStat;
use Tests\TestCase;

class UrlStatTest extends TestCase
{
    /** @test */
    public function belongs_to_url()
    {
        $url = factory(Url::class)->create();

        $url_stat = factory(UrlStat::class)->create([
            'url_id' => $url->id,
        ]);

        $this->assertTrue($url_stat->url()->exists());
    }
}
