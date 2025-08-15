<?php

namespace Tests\Unit\Services;

use App\Enums\UserType;
use App\Models\Url;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;
use Jaybizzle\CrawlerDetect\CrawlerDetect;
use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

#[PHPUnit\Group('services')]
class UserServiceTest extends TestCase
{
    /**
     * Number of guests who have different signatures.
     */
    #[PHPUnit\Test]
    public function guestUsers(): void
    {
        Url::factory()->count(2)->guest()->create();
        $this->assertSame(2, app(UserService::class)->guestUsers());
    }

    /**
     * All guests who have identical signatures should be grouped together.
     */
    #[PHPUnit\Test]
    public function guestUsers2(): void
    {
        Url::factory()->count(5)->guest()->create(['user_uid' => 'foo']);
        $this->assertSame(1, app(UserService::class)->guestUsers());
    }

    /**
     * Tests the signature method of UserService.
     *
     * It verifies that the signature method of UserService returns the correct
     * value for different types of users.
     *
     * - user => user id
     * - guest => random string
     */
    public function testSignature(): void
    {
        $userService = app(UserService::class);
        $this->assertEquals('1b74bf4fdef5c961', $userService->signature());

        $user = $this->basicUser();
        Auth::login($user);
        $this->assertEquals($user->id, $userService->signature());
    }

    #[PHPUnit\Test]
    public function userType_User(): void
    {
        Auth::login(User::factory()->create());

        $this->assertSame(UserType::User, app(UserService::class)->userType());

        // User logged in and with bot user agent
        $this->partialMock(CrawlerDetect::class)
            ->shouldReceive(['isCrawler' => true]);
        $this->assertSame(UserType::User, app(UserService::class)->userType());
    }

    #[PHPUnit\Test]
    public function userType_Guest(): void
    {
        $this->assertSame(UserType::Guest, app(UserService::class)->userType());
    }

    #[PHPUnit\Test]
    public function userType_Bot(): void
    {
        $this->partialMock(CrawlerDetect::class)
            ->shouldReceive(['isCrawler' => true]);

        $this->assertSame(UserType::Bot, app(UserService::class)->userType());
    }
}
