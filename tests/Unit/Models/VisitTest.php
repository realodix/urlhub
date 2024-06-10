<?php

namespace Tests\Unit\Models;

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
    public function currentUserLinkVisitCount(): void
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
        $this->assertSame($nCurrentUser, $this->visit->currentUserLinkVisitCount());
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
            ]))
            ->create();

        $this->assertSame($nGuest, $this->visit->guestUserLinkVisitCount());
        $this->assertSame($nUser + $nGuest, $this->visit->count());
    }
}
