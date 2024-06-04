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
    public function currentUserUrlVisitCount(): void
    {
        $user = $this->normalUser();
        $nCurrentUser = 8;
        $nUser = 6;

        Visit::factory()->count($nCurrentUser)
            ->for(Url::factory()->state(['user_id' => $user->id]))
            ->create();
        Visit::factory()->count($nUser)
            ->for(Url::factory())
            ->create();

        $this->actingAs($user);
        $this->assertSame($nCurrentUser, $this->visit->currentUserUrlVisitCount());
        $this->assertSame($nCurrentUser + $nUser, $this->visit->userClickCount());
    }

    #[PHPUnit\Test]
    public function userClickCount(): void
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

        $this->assertSame($nUser, $this->visit->userClickCount());
        $this->assertSame($nUser + $nGuest, $this->visit->count());
    }

    #[PHPUnit\Test]
    public function guestUserUrlVisitCount(): void
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

        $this->assertSame($nGuest, $this->visit->guestUserUrlVisitCount());
        $this->assertSame($nUser + $nGuest, $this->visit->count());
    }
}
