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

    protected function totalShortUrlById($value = null)
    {
        return app(DashboardController::class)->totalShortUrlById($value);
    }

    protected function totalClicksById($value = null)
    {
        return app(DashboardController::class)->totalClicksById($value);
    }

    /** @test */
    public function total_short_url_by_me()
    {
        $this->assertEquals(1, $this->totalShortUrlById($this->admin()->id));
    }

    /** @test */
    public function total_short_url_by_guest()
    {
        $this->assertEquals(2, $this->totalShortUrlById());
    }

    /** @test */
    public function total_clicks_by_me()
    {
        $this->assertEquals(10, $this->totalClicksById($this->admin()->id));
    }

    /** @test */
    public function total_clicks_by_guest()
    {
        $this->assertEquals(20, $this->totalClicksById());
    }
}
