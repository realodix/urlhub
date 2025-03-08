<?php

namespace Tests\Feature\ServiceProvider;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

class CarbonMacroTest extends TestCase
{
    #[PHPUnit\Test]
    public function it_returns_carbon_instance_in_user_timezone()
    {
        // 1. Mock Auth::user()
        $user = new \stdClass;
        $user->timezone = 'America/Los_Angeles';
        Auth::shouldReceive('user')->andReturn($user);

        // 2. Mock config('app.timezone')
        $this->mock(Illuminate\Config\Repository::class, function (MockInterface $mock) {
            $mock->shouldReceive('get')->with('app.timezone')->andReturn('UTC');
        });

        // 3. Create a Carbon instance (UTC)
        $utcTime = Carbon::create(2024, 10, 27, 10, 0, 0, 'UTC');

        // 4. Call the macro.
        $userTime = $utcTime->inUserTz();

        // 5. Assertions.
        $this->assertInstanceOf(Carbon::class, $userTime);
        $this->assertEquals('America/Los_Angeles', $userTime->timezone->getName());
        $this->assertEquals('2024-10-27 03:00:00', $userTime->toDateTimeString()); // UTC time converted to LA
    }

    #[PHPUnit\Test]
    public function it_returns_carbon_instance_in_app_timezone_when_user_timezone_is_null()
    {
        // 1. Mock Auth::user() with null timezone
        $user = new \stdClass;
        $user->timezone = null;
        Auth::shouldReceive('user')->andReturn($user);

        // 2. Mock config('app.timezone')
        $this->mock(Illuminate\Config\Repository::class, function (MockInterface $mock) {
            $mock->shouldReceive('get')->with('app.timezone')->andReturn('UTC');
        });

        // 3. Create a Carbon instance (UTC)
        $utcTime = Carbon::create(2024, 10, 27, 10, 0, 0, 'UTC');

        // 4. Call the macro.
        $userTime = $utcTime->inUserTz();

        // 5. Assertions.
        $this->assertInstanceOf(Carbon::class, $userTime);
        $this->assertEquals('UTC', $userTime->timezone->getName());
        $this->assertEquals('2024-10-27 10:00:00', $userTime->toDateTimeString()); // Time remains UTC
    }
}
