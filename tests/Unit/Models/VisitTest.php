<?php

namespace Tests\Unit\Models;

use App\Enums\UserType;
use App\Models\Url;
use App\Models\User;
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

    public function testFactory(): void
    {
        $m = Visit::factory()->guest()->create();

        $this->assertSame(\App\Enums\UserType::Guest, $m->user_type);
    }

    #[PHPUnit\Test]
    public function belongsToUrlModel(): void
    {
        $visit = Visit::factory()->create();

        $this->assertEquals(1, $visit->url->count());
        $this->assertInstanceOf(Url::class, $visit->url);
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
        $this->assertSame($nCurrentUser, $this->visit->authUserLinkVisits());
        $this->assertSame($nCurrentUser + $nUser, $this->visit->userLinkVisits());
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

        $this->assertSame($nUser, $this->visit->userLinkVisits());
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

        $this->assertSame($nGuest, $this->visit->guestLinkVisits());
        $this->assertSame($nUser + $nGuest, $this->visit->count());
    }

    #[PHPUnit\Test]
    public function userVisits()
    {
        $this->visitCountData();

        $this->assertEquals(1, $this->visit->userVisits());
    }

    #[PHPUnit\Test]
    public function guestVisits()
    {
        $this->visitCountData();

        $this->assertEquals(5, $this->visit->guestVisits());
    }

    #[PHPUnit\Test]
    public function uniqueGuestVisits()
    {
        $this->visitCountData();

        $this->assertEquals(3, $this->visit->uniqueGuestVisits());
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

    public function test_get_top_referrers()
    {
        Visit::factory()->count(5)->create();
        $topReferrers = Visit::getTopReferrers();
        $this->assertEquals($topReferrers->first()->total, $topReferrers->max('total'));

        // Unique referrers
        $this->assertEquals(1, $topReferrers->count());
        Visit::factory()->count(5)->create(['referer' => 'foo']);
        $topReferrers = Visit::getTopReferrers();
        $this->assertEquals(2, $topReferrers->count());
    }

    public function test_get_top_referrers_for_auth_user()
    {
        // Create a user and authenticate them
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create some URLs, some belonging to the user, some not
        $userUrl1 = Url::factory()->create(['user_id' => $user->id]);
        Visit::factory()->count(5)->for($userUrl1)->create(['referer' => 'https://example.com']);
        $userUrl2 = Url::factory()->create(['user_id' => $user->id]);
        Visit::factory()->count(3)->for($userUrl2)->create(['referer' => 'https://google.com']);
        $userUrl3 = Url::factory()->create(['user_id' => $user->id]);
        Visit::factory()->count(2)->for($userUrl3)->create(['referer' => 'https://twitter.com']);
        $userUrl4 = Url::factory()->create(['user_id' => $user->id]);
        Visit::factory()->count(1)->for($userUrl4)->create(['referer' => 'https://instagram.com']);
        $userUrl5 = Url::factory()->create(['user_id' => $user->id]);
        Visit::factory()->count(6)->for($userUrl5)->create(['referer' => 'https://example.com']);
        $userUrl6 = Url::factory()->create(['user_id' => $user->id]);
        Visit::factory()->count(4)->for($userUrl6)->create(['referer' => 'https://bing.com']);

        $otherUserUrl = Url::factory()->create();
        Visit::factory()->count(7)->for($otherUserUrl)->create(['referer' => 'https://facebook.com']);

        // Get the top referrers for the authenticated user
        $topReferrers = Visit::getTopReferrersForAuthUser($user);

        // Assertions
        $this->assertCount(5, $topReferrers);

        // Check if the referrers are ordered correctly by visit count
        $this->assertEquals('https://example.com', $topReferrers[0]->referer);
        $this->assertEquals(11, $topReferrers[0]->total);
        $this->assertEquals('https://bing.com', $topReferrers[1]->referer);
        $this->assertEquals(4, $topReferrers[1]->total);
        $this->assertEquals('https://google.com', $topReferrers[2]->referer);
        $this->assertEquals(3, $topReferrers[2]->total);
        $this->assertEquals('https://twitter.com', $topReferrers[3]->referer);
        $this->assertEquals(2, $topReferrers[3]->total);
        $this->assertEquals('https://instagram.com', $topReferrers[4]->referer);
        $this->assertEquals(1, $topReferrers[4]->total);

        // Check if referrers from other users are not included
        $this->assertNotContains('https://facebook.com', $topReferrers->pluck('referer')->toArray());
    }

    public function test_get_top_referrers_for_auth_user_with_no_visits()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $topReferrers = Visit::getTopReferrersForAuthUser($user);
        $this->assertCount(0, $topReferrers);
    }
}
