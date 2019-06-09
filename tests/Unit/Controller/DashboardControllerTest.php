<?php

namespace Tests\Unit\Controller;

use App\Url;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    public function setUp():void
    {
        parent::setUp();

        factory(Url::class)->create([
            'user_id'  => 0,
            'long_url' => 'https://laravel.com',
            'clicks'   => 10,
            'ip'       => '0.0.0.0',
        ]);

        factory(Url::class)->create([
            'user_id'  => 0,
            'long_url' => 'https://laravel.com',
            'clicks'   => 10,
            'ip'       => '1.1.1.1',
        ]);
    }

    /** @test */
    public function total_guest()
    {
        $count = Url::select('ip', DB::raw('count(*) as total'))
                      ->whereNull('user_id')
                      ->groupBy('ip')
                      ->get()
                      ->count();

        $this->assertSame(2, $count);
    }
}
