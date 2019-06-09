<?php

namespace Tests\Unit\Controller;

use App\Http\Controllers\Backend\DashboardController;
use App\Url;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    public function setUp():void
    {
        parent::setUp();

        factory(Url::class)->create([
            'user_id'  => $this->admin()->id,
            'long_url' => 'https://laravel.com',
            'clicks'   => 10,
            'ip'       => '0.0.0.0',
        ]);

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
    public function total_short_url_by_me()
    {
        $this->assertEquals(1, app(DashboardController::class)->totalShortUrlById($this->admin()->id));
    }

    /** @test */
    public function total_short_url_by_guest()
    {
        $this->assertEquals(2, app(DashboardController::class)->totalShortUrlById());
    }

    /** @test */
    public function total_clicks_by_me()
    {
        $this->assertEquals(10, app(DashboardController::class)->totalClicksById($this->admin()->id));
    }

    /** @test */
    public function total_clicks_by_guest()
    {
        $this->assertEquals(20, app(DashboardController::class)->totalClicksById());
    }

    /** @test */
    public function total_guest()
    {
        $count = Url::select('ip', DB::raw('count(*) as total'))
                      ->whereNull('user_id')
                      ->groupBy('ip')
                      ->get()
                      ->count();

        $this->assertEquals(2, $count);
    }
}
