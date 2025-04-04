<?php

namespace Tests\Unit\Services;

use App\Enums\UserType;
use App\Models\Url;
use App\Models\Visit;
use App\Services\UserService;
use App\Services\VisitService;
use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

#[PHPUnit\Group('services')]
class VisitServiceTest extends TestCase
{
    private Visit $visit;

    private VisitService $visitService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->visit = new Visit;
        $this->visitService = app(VisitService::class);
    }

    #[PHPUnit\Test]
    public function isFirstClick(): void
    {
        $visitor = app(VisitService::class);

        // First visit
        $url = Url::factory()->create();
        $this->assertTrue($visitor->isFirstClick($url));

        // Second visit and so on
        $url = Url::factory()->create();
        Visit::factory()->for($url)->create([
            'user_uid' => app(UserService::class)->signature(),
        ]);
        $this->assertFalse($visitor->isFirstClick($url));
    }

    #[PHPUnit\Test]
    public function getRefererHost(): void
    {
        $visitor = app(VisitService::class);

        $this->assertSame(null, $visitor->getRefererHost(null));
        $this->assertSame(
            'https://github.com',
            $visitor->getRefererHost('https://github.com/laravel'),
        );
        $this->assertSame(
            'http://urlhub.test',
            $visitor->getRefererHost('http://urlhub.test/admin?page=2'),
        );
    }

    #[PHPUnit\Test]
    public function authUserLinkVisits(): void
    {
        $user = $this->basicUser();
        $nCurrentUser = 8;
        $nUser = 6;

        Visit::factory()->count($nCurrentUser)
            ->for(Url::factory()->state(['user_id' => $user->id]))
            ->create();
        Visit::factory()->count($nUser)
            ->for(Url::factory())
            ->create();

        $this->actingAs($user);
        $this->assertSame($nCurrentUser, $this->visitService->authUserLinkVisits());
        $this->assertSame($nCurrentUser + $nUser, $this->visitService->userLinkVisits());
    }

    #[PHPUnit\Test]
    public function userLinkVisits(): void
    {
        $nUser = 6;
        $nGuest = 4;

        Visit::factory()->count($nUser)
            ->for(Url::factory())
            ->create();

        Visit::factory()->count($nGuest)
            ->for(Url::factory()->guest())
            ->create();

        $this->assertSame($nUser, $this->visitService->userLinkVisits());
        $this->assertSame($nUser + $nGuest, $this->visit->count());
    }

    #[PHPUnit\Test]
    public function guestLinkVisits(): void
    {
        $nUser = 6;
        $nGuest = 4;

        Visit::factory()->count($nUser)
            ->for(Url::factory())
            ->create();

        Visit::factory()->count($nGuest)
            ->for(Url::factory()->guest())
            ->create();

        $this->assertSame($nGuest, $this->visitService->guestLinkVisits());
        $this->assertSame($nUser + $nGuest, $this->visit->count());
    }

    #[PHPUnit\Test]
    public function userVisits()
    {
        $this->visitCountData();

        $this->assertEquals(1, $this->visitService->userVisits());
    }

    #[PHPUnit\Test]
    public function guestVisits()
    {
        $this->visitCountData();

        $this->assertEquals(5, $this->visitService->guestVisits());
    }

    #[PHPUnit\Test]
    public function uniqueGuestVisits()
    {
        $this->visitCountData();

        $this->assertEquals(3, $this->visitService->uniqueGuestVisits());
    }

    private function visitCountData()
    {
        Visit::factory()->create(); // user1
        Visit::factory()->guest()->create(); // guest1
        Visit::factory()->guest()->count(2)->create(['user_uid' => 'foo']); // guest2
        Visit::factory()->count(2)->create([ // bot
            'user_type' => UserType::Bot,
            'user_uid' => 'bar',
        ]);
    }
}
