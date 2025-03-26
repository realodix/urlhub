<?php

namespace Tests\Unit\Services;

use App\Enums\UserType;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;
use Jaybizzle\CrawlerDetect\CrawlerDetect;
use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

#[PHPUnit\Group('services')]
class UserServiceTest extends TestCase
{
    public function testSignature(): void
    {
        $userService = app(UserService::class);
        $this->assertEquals('1b74bf4fdef5c961', $userService->signature());

        $user = $this->basicUser();
        Auth::login($user);
        $this->assertEquals($user->id, $userService->signature());
    }

    public function testUserTypesUser(): void
    {
        Auth::login(User::factory()->create());

        $this->assertSame(UserType::User, app(UserService::class)->userType());

        // User logged in and with bot user agent
        $this->partialMock(CrawlerDetect::class)
            ->shouldReceive(['isCrawler' => true]);
        $this->assertSame(UserType::User, app(UserService::class)->userType());
    }

    public function testUserTypesGuest(): void
    {
        $this->assertSame(UserType::Guest, app(UserService::class)->userType());
    }

    public function testUserTypesBot(): void
    {
        $this->partialMock(CrawlerDetect::class)
            ->shouldReceive(['isCrawler' => true]);

        $this->assertSame(UserType::Bot, app(UserService::class)->userType());
    }
}
