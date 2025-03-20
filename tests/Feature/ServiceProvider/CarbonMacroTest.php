<?php

namespace Tests\Feature\ServiceProvider;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

class CarbonMacroTest extends TestCase
{
    #[PHPUnit\Test]
    public function it_returns_carbon_instance_in_user_timezone()
    {
        config(['app.timezone' => 'Europe/London']);

        // 1. Mock Auth::user()
        $user = new \stdClass;
        $user->timezone = 'America/Los_Angeles';
        Auth::shouldReceive('user')->andReturn($user);

        // 2. Create a Carbon instance
        $utcTime = Carbon::create('2024-10-27 10:00:00');

        // 3. Call the macro.
        $userTime = $utcTime->inUserTz();

        // 4. Assertions.
        $this->assertInstanceOf(Carbon::class, $userTime);
        $this->assertEquals('America/Los_Angeles', $userTime->timezone->getName());
        $this->assertEquals('2024-10-27 03:00:00', $userTime->toDateTimeString()); // UTC time converted to LA
    }

    #[PHPUnit\Test]
    public function it_returns_carbon_instance_in_app_timezone_when_user_timezone_is_null()
    {
        config(['app.timezone' => 'Europe/London']);

        // 1. Mock Auth::user() with null timezone
        $user = new \stdClass;
        $user->timezone = null;
        Auth::shouldReceive('user')->andReturn($user);

        // 2. Create a Carbon instance
        $utcTime = Carbon::create('2024-10-27 10:00:00');

        // 3. Call the macro.
        $userTime = $utcTime->inUserTz();

        // 4. Assertions.
        $this->assertInstanceOf(Carbon::class, $userTime);
        $this->assertEquals('Europe/London', $userTime->timezone->getName());
        $this->assertEquals('2024-10-27 10:00:00', $userTime->toDateTimeString()); // Time remains UTC
    }
}
