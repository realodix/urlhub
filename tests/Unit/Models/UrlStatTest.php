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
        factory(Url::class)->create([
            'id'      => 1,
            'user_id' => $this->admin()->id,
        ]);

        $url_stat = factory(UrlStat::class)->create([
            'url_id' => 1,
        ]);

        $this->assertTrue($url_stat->url()->exists());
    }
}
