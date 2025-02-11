<?php

namespace Tests\Unit\Models;

use App\Enums\UserType;
use App\Models\Url;
use App\Models\Visit;
use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

#[PHPUnit\Group('model')]
class VisitTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->visit = new Visit;
    }

    #[PHPUnit\Test]
    public function belongsToUrlModel(): void
    {
        $visit = Visit::factory()->create();

        $this->assertEquals(1, $visit->url->count());
        $this->assertInstanceOf(Url::class, $visit->url);
    }

    #[PHPUnit\Test]
    public function authUserLinkVisitCount(): void
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
        $this->assertSame($nCurrentUser, $this->visit->authUserLinkVisitCount());
        $this->assertSame($nCurrentUser + $nUser, $this->visit->userLinkVisitCount());
    }

    #[PHPUnit\Test]
    public function userLinkVisitCount(): void
    {
        $nUser = 6;
        $nGuest = 4;

        Visit::factory()->count($nUser)
            ->for(Url::factory())
            ->create();

        Visit::factory()->count($nGuest)
            ->for(Url::factory()->state([
                'user_id' => Url::GUEST_ID,
                'user_type' => UserType::Guest,
            ]))
            ->create();

        $this->assertSame($nUser, $this->visit->userLinkVisitCount());
        $this->assertSame($nUser + $nGuest, $this->visit->count());
    }

    #[PHPUnit\Test]
    public function guestUserLinkVisitCount(): void
    {
        $nUser = 6;
        $nGuest = 4;

        Visit::factory()->count($nUser)
            ->for(Url::factory())
            ->create();

        Visit::factory()->count($nGuest)
            ->for(Url::factory()->state([
                'user_id' => Url::GUEST_ID,
                'user_type' => UserType::Guest,
            ]))
            ->create();

        $this->assertSame($nGuest, $this->visit->guestUserLinkVisitCount());
        $this->assertSame($nUser + $nGuest, $this->visit->count());
    }

    #[PHPUnit\Test]
    public function userVisitCount()
    {
        $this->visitCountData();

        $this->assertEquals(1, $this->visit->userVisitCount());
    }

    #[PHPUnit\Test]
    public function guestVisitCount()
    {
        $this->visitCountData();

        $this->assertEquals(3, $this->visit->guestVisitCount());
    }

    #[PHPUnit\Test]
    public function uniqueGuestVisitCount()
    {
        $this->visitCountData();

        $this->assertEquals(2, $this->visit->uniqueGuestVisitCount());
    }

    private function visitCountData()
    {
        Visit::factory()->create(); // user1
        Visit::factory()->create(['user_type' => UserType::Guest, 'user_uid' => 'ff6db461ccba3fa6']); // guest1
        Visit::factory()->create(['user_type' => UserType::Guest, 'user_uid' => '52df013b71713cd1']); // guest2
        Visit::factory()->create(['user_type' => UserType::Guest, 'user_uid' => '52df013b71713cd1']); // guest2
    }
}
